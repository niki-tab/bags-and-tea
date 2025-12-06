<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Vinted\Application;

use Exception;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent;

/**
 * Phase 1: Scrapes Vinted search results page to get list of items
 */
final class ScrapeVintedSearchResults
{
    public function __construct(
        private readonly FetchWebpageContent $fetchWebpageContent
    ) {
    }

    public function __invoke(string $searchUrl): ?string
    {
        try {
            Log::info('Scraping Vinted search results', [
                'url' => $searchUrl,
            ]);

            $html = ($this->fetchWebpageContent)($searchUrl);

            if (empty($html)) {
                Log::warning('Empty HTML returned from Vinted search', [
                    'url' => $searchUrl,
                ]);
                return null;
            }

            Log::info('Successfully scraped Vinted search results', [
                'url' => $searchUrl,
                'html_length' => strlen($html),
            ]);

            return $html;

        } catch (Exception $e) {
            Log::error('Failed to scrape Vinted search results', [
                'url' => $searchUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
