<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Vinted\Application;

use Exception;
use Illuminate\Support\Facades\Log;
use Src\ThirdPartyServices\Firecrawl\Application\Fetch\FetchWebpageContent;

/**
 * Phase 2: Scrapes individual Vinted listing page to get detailed info (images, upload date)
 */
final class ScrapeVintedListingDetails
{
    public function __construct(
        private readonly FetchWebpageContent $fetchWebpageContent
    ) {
    }

    /**
     * Scrape a single listing page and extract images + upload date
     */
    public function __invoke(string $listingUrl): ?array
    {
        try {
            Log::info('Scraping Vinted listing details', ['url' => $listingUrl]);

            $html = ($this->fetchWebpageContent)($listingUrl);

            if (empty($html)) {
                Log::warning('Empty HTML returned from Vinted listing', ['url' => $listingUrl]);
                return null;
            }

            $images = $this->extractImages($html);
            $uploadedText = $this->extractUploadedText($html);
            $description = $this->extractDescription($html);

            Log::info('Successfully scraped listing details', [
                'url' => $listingUrl,
                'images_found' => count($images),
                'uploaded_text' => $uploadedText,
                'has_description' => !empty($description),
            ]);

            return [
                'images' => $images,
                'uploaded_text' => $uploadedText,
                'description' => $description,
            ];

        } catch (Exception $e) {
            Log::error('Failed to scrape Vinted listing details', [
                'url' => $listingUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Extract up to 5 product images from the listing page
     */
    private function extractImages(string $html): array
    {
        $images = [];
        $seenUrls = [];

        // Pattern 1: Look for high-res Vinted image URLs
        // Format: https://images1.vinted.net/t/...
        preg_match_all(
            '/(https:\/\/images\d*\.vinted\.net\/[^"\'\s>]+\.(?:jpg|jpeg|png|webp)[^"\'\s>]*)/i',
            $html,
            $matches
        );

        foreach ($matches[1] as $imageUrl) {
            // Clean up URL (remove trailing chars that aren't part of URL)
            $imageUrl = preg_replace('/["\'\s>].*$/', '', $imageUrl);

            // Skip very small thumbnails
            if (preg_match('/\/f50\/|\/50x|\/thumb/', $imageUrl)) {
                continue;
            }

            // Normalize URL to avoid duplicates (remove query params for comparison)
            $normalizedUrl = preg_replace('/\?.*$/', '', $imageUrl);

            if (!in_array($normalizedUrl, $seenUrls)) {
                $seenUrls[] = $normalizedUrl;
                $images[] = $imageUrl;
            }

            // Limit to 5 images
            if (count($images) >= 5) {
                break;
            }
        }

        return $images;
    }

    /**
     * Extract the upload date text (e.g., "Subido hace 5 horas")
     */
    private function extractUploadedText(string $html): ?string
    {
        // Pattern 1: "Subido hace X horas/días/semanas" (Spanish)
        if (preg_match('/Subido hace\s+(\d+\s+(?:segundos?|minutos?|horas?|d[íi]as?|semanas?|meses?|a[ñn]os?))/ui', $html, $match)) {
            return 'Subido hace ' . $match[1];
        }

        // Pattern 2: Just "hace X tiempo" in context
        if (preg_match('/hace\s+(\d+\s+(?:segundos?|minutos?|horas?|d[íi]as?|semanas?|meses?|a[ñn]os?))/ui', $html, $match)) {
            return 'Subido hace ' . $match[1];
        }

        // Pattern 3: Try English version too "Uploaded X ago"
        if (preg_match('/Uploaded\s+(\d+\s+(?:seconds?|minutes?|hours?|days?|weeks?|months?|years?))\s+ago/i', $html, $match)) {
            return 'Uploaded ' . $match[1] . ' ago';
        }

        // Pattern 4: "Added X ago"
        if (preg_match('/Added\s+(\d+\s+(?:seconds?|minutes?|hours?|days?|weeks?|months?|years?))\s+ago/i', $html, $match)) {
            return 'Added ' . $match[1] . ' ago';
        }

        // Pattern 5: Look in JSON data for timestamp
        if (preg_match('/"created_at_ts":\s*(\d+)/', $html, $match)) {
            $timestamp = (int) $match[1];
            $diff = time() - $timestamp;
            return $this->formatTimeDiff($diff);
        }

        return null;
    }

    private function formatTimeDiff(int $seconds): string
    {
        if ($seconds < 60) {
            return 'Subido hace ' . $seconds . ' segundos';
        } elseif ($seconds < 3600) {
            $minutes = (int) floor($seconds / 60);
            return 'Subido hace ' . $minutes . ' ' . ($minutes === 1 ? 'minuto' : 'minutos');
        } elseif ($seconds < 86400) {
            $hours = (int) floor($seconds / 3600);
            return 'Subido hace ' . $hours . ' ' . ($hours === 1 ? 'hora' : 'horas');
        } elseif ($seconds < 604800) {
            $days = (int) floor($seconds / 86400);
            return 'Subido hace ' . $days . ' ' . ($days === 1 ? 'día' : 'días');
        } elseif ($seconds < 2592000) {
            $weeks = (int) floor($seconds / 604800);
            return 'Subido hace ' . $weeks . ' ' . ($weeks === 1 ? 'semana' : 'semanas');
        } else {
            $months = (int) floor($seconds / 2592000);
            return 'Subido hace ' . $months . ' ' . ($months === 1 ? 'mes' : 'meses');
        }
    }

    /**
     * Extract the product description from the listing page
     */
    private function extractDescription(string $html): ?string
    {
        // Pattern 1: Look for description in JSON-LD or data attributes
        if (preg_match('/"description":\s*"([^"]+)"/', $html, $match)) {
            return html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
        }

        // Pattern 2: Look for description in meta tag
        if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/', $html, $match)) {
            return html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
        }

        // Pattern 3: Look for description in a specific div (Vinted uses data-testid)
        if (preg_match('/data-testid="item-description"[^>]*>([^<]+)</i', $html, $match)) {
            return trim(html_entity_decode($match[1], ENT_QUOTES, 'UTF-8'));
        }

        // Pattern 4: Look for "Descripción" section in Spanish
        if (preg_match('/Descripci[óo]n[^<]*<[^>]*>([^<]{20,})</ui', $html, $match)) {
            return trim(html_entity_decode($match[1], ENT_QUOTES, 'UTF-8'));
        }

        return null;
    }
}
