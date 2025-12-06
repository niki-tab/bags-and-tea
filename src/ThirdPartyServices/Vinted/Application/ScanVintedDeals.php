<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Vinted\Application;

use OpenAI\Client;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent;
use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;

final class ScanVintedDeals
{
    private FetchWebpageContent $fetcher;
    private ScrapeVintedSearchResults $scraper;
    private ParseVintedSearchResults $parser;

    public function __construct(Client $openaiClient)
    {
        $this->fetcher = new FetchWebpageContent();
        $this->scraper = new ScrapeVintedSearchResults($this->fetcher);
        $this->parser = new ParseVintedSearchResults($openaiClient);
    }

    /**
     * Scan all active search queries
     */
    public function scanAll(?callable $onProgress = null): array
    {
        $queries = BagSearchQueryEloquentModel::where('is_active', true)->get();

        $results = [
            'queries_processed' => 0,
            'total_listings' => 0,
            'new_interesting_deals' => 0,
            'errors' => [],
        ];

        foreach ($queries as $query) {
            try {
                if ($onProgress) {
                    $onProgress("Scanning: {$query->name} ({$query->brand})");
                }

                $queryResult = $this->scanQuery($query, $onProgress);

                $results['queries_processed']++;
                $results['total_listings'] += $queryResult['total_listings'];
                $results['new_interesting_deals'] += $queryResult['new_interesting_deals'];

            } catch (\Exception $e) {
                $results['errors'][] = [
                    'query' => $query->name,
                    'error' => $e->getMessage(),
                ];
                Log::error("Failed to scan query: {$query->name}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Scan a single search query
     */
    public function scanQuery(BagSearchQueryEloquentModel $query, ?callable $onProgress = null): array
    {
        $savedCount = 0;
        $newInterestingCount = 0;
        $maxPages = $query->max_pages ?? 3;
        $pagesScraped = 0;

        for ($page = 1; $page <= $maxPages; $page++) {
            $pageUrl = $query->getUrlForPage($page);

            if ($onProgress) {
                $onProgress("  Page {$page}/{$maxPages}...");
            }

            Log::info("Scraping page {$page}/{$maxPages}", ['url' => $pageUrl]);

            // Scrape
            $html = ($this->scraper)($pageUrl);

            if ($html === null) {
                Log::warning("Failed to scrape page {$page}, stopping pagination");
                break;
            }

            $pagesScraped++;

            // Parse with AI
            $parsed = ($this->parser)($html, $pageUrl);
            $listings = $parsed['listings'] ?? [];

            if (empty($listings)) {
                Log::info("No listings found on page {$page}, stopping pagination");
                break;
            }

            // Save listings (only those within price range)
            $minPrice = (float) ($query->min_price ?? 50);
            $maxPrice = (float) ($query->max_price ?? 10000);

            foreach ($listings as $listing) {
                if (empty($listing['vinted_item_id'])) {
                    continue;
                }

                $price = $listing['price'];

                // Skip listings outside the price range
                if ($price === null || $price < $minPrice || $price > $maxPrice) {
                    continue;
                }

                $isInteresting = $price <= (float) $query->ideal_price;
                $existingListing = VintedListingEloquentModel::where('vinted_item_id', $listing['vinted_item_id'])->first();

                VintedListingEloquentModel::updateOrCreate(
                    ['vinted_item_id' => $listing['vinted_item_id']],
                    [
                        'bag_search_query_id' => $query->id,
                        'title' => $listing['title'],
                        'price' => $price,
                        'currency' => $listing['currency'] ?? 'EUR',
                        'url' => $listing['url'],
                        'main_image_url' => $listing['main_image_url'],
                        'brand_detected' => $listing['brand'],
                        'size' => $listing['size'],
                        'is_interesting' => $isInteresting,
                        'raw_data' => $listing,
                        'scraped_at' => now(),
                    ]
                );

                $savedCount++;

                if ($isInteresting && !$existingListing) {
                    $newInterestingCount++;
                }
            }
        }

        // Update last_scanned_at
        $query->update(['last_scanned_at' => now()]);

        return [
            'pages_scraped' => $pagesScraped,
            'total_listings' => $savedCount,
            'new_interesting_deals' => $newInterestingCount,
        ];
    }
}
