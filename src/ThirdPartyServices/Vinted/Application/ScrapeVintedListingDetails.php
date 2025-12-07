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
            $description = $this->extractDescription($html);

            Log::info('Successfully scraped listing details', [
                'url' => $listingUrl,
                'images_found' => count($images),
                'has_description' => !empty($description),
            ]);

            return [
                'images' => $images,
                'description' => $description,
                'html_content' => $html, // Pass to AI for upload date extraction
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
     *
     * You will find information about the date in which the product was uploaded
     * and information about the last visit. Always take the uploaded information
     * and never the last visit information.
     */
    private function extractUploadedText(string $html): ?string
    {
        // First, try to get the timestamp from JSON data (most reliable)
        if (preg_match('/"created_at_ts":\s*(\d+)/', $html, $match)) {
            $timestamp = (int) $match[1];
            $diff = time() - $timestamp;
            return $this->formatTimeDiff($diff);
        }

        // Remove last visit/connection patterns to avoid confusion
        // These patterns should NOT be matched:
        // - "Última visita hace X" / "Última conexión hace X" (Spanish)
        // - "Last seen X ago" / "Last active X ago" (English)
        // - "Dernière visite il y a X" (French)
        // - "Zuletzt online vor X" (German)
        $htmlWithoutVisits = preg_replace(
            '/([ÚU]ltima\s*(visita|conexi[óo]n)|Last\s*(seen|active|visit)|Derni[èe]re\s*visite|Zuletzt\s*online)[^<\n]*/ui',
            '',
            $html
        );

        // Now look for upload/added patterns in any language
        // Spanish: "Subido hace X"
        if (preg_match('/Subido\s+hace\s*(\d+\s*\w+)/ui', $htmlWithoutVisits, $match)) {
            return 'Subido hace ' . trim($match[1]);
        }

        // Spanish: "Añadido hace X"
        if (preg_match('/A[ñn]adido\s+hace\s*(\d+\s*\w+)/ui', $htmlWithoutVisits, $match)) {
            return 'Subido hace ' . trim($match[1]);
        }

        // English: "Uploaded X ago" or "Added X ago"
        if (preg_match('/(Uploaded|Added)\s+(\d+\s*\w+)\s*ago/i', $htmlWithoutVisits, $match)) {
            return $match[1] . ' ' . trim($match[2]) . ' ago';
        }

        // French: "Ajouté il y a X"
        if (preg_match('/Ajout[ée]\s+il\s+y\s+a\s+(\d+\s*\w+)/ui', $htmlWithoutVisits, $match)) {
            return 'Ajouté il y a ' . trim($match[1]);
        }

        // German: "Hochgeladen vor X"
        if (preg_match('/Hochgeladen\s+vor\s+(\d+\s*\w+)/ui', $htmlWithoutVisits, $match)) {
            return 'Hochgeladen vor ' . trim($match[1]);
        }

        // Italian: "Aggiunto X fa"
        if (preg_match('/Aggiunto\s+(\d+\s*\w+)\s*fa/ui', $htmlWithoutVisits, $match)) {
            return 'Aggiunto ' . trim($match[1]) . ' fa';
        }

        // Generic fallback: look for "hace X" pattern (after removing last visit info)
        if (preg_match('/hace\s+(\d+\s*\w+)/ui', $htmlWithoutVisits, $match)) {
            return 'Subido hace ' . trim($match[1]);
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
