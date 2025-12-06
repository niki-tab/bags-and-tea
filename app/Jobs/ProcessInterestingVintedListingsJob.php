<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterestingDealsFound;
use Src\ThirdPartyServices\Vinted\Application\ScrapeVintedListingDetails;
use Src\ThirdPartyServices\Vinted\Application\VerifyVintedListing;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;
use Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent;

class ProcessInterestingVintedListingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 900; // 15 minutes max (more time to find verified items)
    public int $tries = 1;

    // Target number of verified items to include in email
    private const TARGET_VERIFIED_ITEMS = 10;

    // Only process listings scraped in the last X minutes
    private const SCRAPE_WINDOW_MINUTES = 60;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('Starting phase 2: Processing interesting Vinted listings');

        $cutoffTime = now()->subMinutes(self::SCRAPE_WINDOW_MINUTES);

        $fetcher = new FetchWebpageContent();
        $scraper = new ScrapeVintedListingDetails($fetcher);
        $openaiClient = \OpenAI::client(config('services.openai.api_key'));
        $verifier = new VerifyVintedListing($openaiClient);
        $verifiedListings = [];
        $processedCount = 0;

        // Keep processing until we have enough verified items or run out
        while (count($verifiedListings) < self::TARGET_VERIFIED_ITEMS) {
            // Get next unprocessed listing (cheapest first)
            $listing = VintedListingEloquentModel::where('is_interesting', true)
                ->where('details_scraped', false)
                ->where('scraped_at', '>=', $cutoffTime)
                ->orderBy('price', 'asc')
                ->first();

            if (!$listing) {
                Log::info('No more listings to process', [
                    'verified_so_far' => count($verifiedListings),
                    'total_processed' => $processedCount,
                ]);
                break;
            }

            $processedCount++;

            try {
                Log::info('Processing listing', [
                    'id' => $listing->id,
                    'title' => $listing->title,
                    'price' => $listing->price,
                    'url' => $listing->url,
                    'verified_so_far' => count($verifiedListings),
                    'target' => self::TARGET_VERIFIED_ITEMS,
                ]);

                // Scrape detailed info
                $details = $scraper($listing->url);

                if ($details !== null) {
                    // Get the search query to know what product we're looking for
                    $searchQuery = $listing->searchQuery;
                    $expectedProduct = $searchQuery
                        ? "{$searchQuery->brand} {$searchQuery->name} bag"
                        : 'Louis Vuitton Neverfull bag';

                    // AI verification
                    $verification = $verifier(
                        $expectedProduct,
                        $listing->title,
                        $details['description'] ?? null,
                        $details['images'] ?? []
                    );

                    // Update listing with images, upload text, description, and verification
                    $listing->update([
                        'images' => $details['images'],
                        'uploaded_text' => $details['uploaded_text'],
                        'description' => $details['description'],
                        'is_verified_product' => $verification['is_verified'],
                        'verification_reason' => $verification['reason'],
                        'details_scraped' => true,
                    ]);

                    Log::info('Listing processed', [
                        'id' => $listing->id,
                        'is_verified' => $verification['is_verified'],
                        'reason' => $verification['reason'],
                    ]);

                    // Add to verified list if it's a real product
                    if ($verification['is_verified']) {
                        $verifiedListings[] = $listing->fresh();
                        Log::info('Found verified product!', [
                            'title' => $listing->title,
                            'price' => $listing->price,
                            'verified_count' => count($verifiedListings),
                        ]);
                    }
                } else {
                    // Mark as scraped even if failed
                    $listing->update([
                        'details_scraped' => true,
                        'is_verified_product' => false,
                        'verification_reason' => 'Failed to scrape listing details',
                    ]);
                    Log::warning('Failed to get details for listing', ['id' => $listing->id]);
                }

                // Small delay between requests to avoid rate limiting
                usleep(500000); // 0.5 second

            } catch (\Exception $e) {
                // Mark as processed to avoid infinite loop
                $listing->update(['details_scraped' => true]);
                Log::error('Error processing listing', [
                    'id' => $listing->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Phase 2 processing complete', [
            'total_processed' => $processedCount,
            'verified_count' => count($verifiedListings),
        ]);

        // Send email with verified listings
        if (!empty($verifiedListings)) {
            Log::info('Sending email notification', ['listings_count' => count($verifiedListings)]);

            Mail::to('nicolas.tabares.tech@gmail.com')
                ->send(new InterestingDealsFound(collect($verifiedListings)));

            // Mark as notified
            foreach ($verifiedListings as $listing) {
                $listing->update(['notification_sent' => true]);
            }

            Log::info('Email notification sent successfully');
        } else {
            Log::info('No verified listings to notify about');
        }
    }
}
