<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Vinted\Application\ScrapeVintedListingDetails;
use Src\ThirdPartyServices\Vinted\Application\VerifyVintedListing;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;
use Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent;

class VerifySingleVintedListingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120; // 2 minutes max per listing
    public int $tries = 2;

    public function __construct(
        private string $listingId
    ) {
    }

    public function handle(): void
    {
        $listing = VintedListingEloquentModel::find($this->listingId);

        if (!$listing) {
            Log::warning('Listing not found', ['id' => $this->listingId]);
            return;
        }

        if ($listing->is_verified_product !== null) {
            Log::info('Listing already verified', ['id' => $this->listingId]);
            return;
        }

        try {
            Log::info('Processing listing', [
                'id' => $listing->id,
                'title' => $listing->title,
                'price' => $listing->price,
            ]);

            $fetcher = new FetchWebpageContent();
            $scraper = new ScrapeVintedListingDetails($fetcher);
            $openaiClient = \OpenAI::client(config('services.openai.api_key'));
            $verifier = new VerifyVintedListing($openaiClient);

            // Scrape detailed info
            $details = $scraper($listing->url);

            if ($details !== null) {
                // Get the search query to know what product we're looking for
                $searchQuery = $listing->searchQuery;
                $expectedProduct = $searchQuery
                    ? "{$searchQuery->brand} {$searchQuery->name} bag"
                    : 'Louis Vuitton Neverfull bag';

                // AI verification (also extracts upload date from HTML)
                $verification = $verifier(
                    $expectedProduct,
                    $listing->title,
                    $details['description'] ?? null,
                    $details['html_content'] ?? null
                );

                // Build update data - always save scraped details
                $updateData = [
                    'images' => $details['images'],
                    'description' => $details['description'],
                    'details_scraped' => true,
                ];

                // Only update verification fields if AI returned a definitive answer (not null)
                // When AI fails (rate limit, etc.), is_verified will be null - keep it for retry
                if ($verification['is_verified'] !== null) {
                    $updateData['is_verified_product'] = $verification['is_verified'];
                    $updateData['verification_reason'] = $verification['reason'];
                    $updateData['uploaded_text'] = $verification['uploaded_text'];

                    Log::info('Listing verified', [
                        'id' => $listing->id,
                        'is_verified' => $verification['is_verified'],
                        'reason' => $verification['reason'],
                        'uploaded_text' => $verification['uploaded_text'],
                    ]);
                } else {
                    // AI failed (rate limit, error, etc.) - will retry in next batch
                    Log::warning('AI verification returned null, will retry later', [
                        'id' => $listing->id,
                        'reason' => $verification['reason'],
                    ]);
                }

                $listing->update($updateData);
            } else {
                // Keep is_verified_product as NULL so it will be retried in next batch
                Log::warning('Failed to scrape listing details, will retry later', ['id' => $listing->id]);
            }

        } catch (\Exception $e) {
            // Keep is_verified_product as NULL so it will be retried in next batch
            Log::error('Error processing listing, will retry later', [
                'id' => $listing->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
