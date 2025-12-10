<?php
declare(strict_types=1);

namespace Src\ThirdPartyServices\Firecrawl\Application\Fetch;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Src\ThirdPartyServices\Firecrawl\Domain\Exceptions\FirecrawlApiException;

final class FetchWebpageContent
{   
    private readonly string $apiKey;
    private string $apiUrl = 'https://api.firecrawl.dev/v1/scrape';

    public function __construct()
    {
        $this->apiKey = config('services.firecrawl.api_key');
    }
    
    public function __invoke(string $webUrl): ?string
    {
        // Check if this is an XML file (like sitemap.xml)
        $isXmlUrl = str_ends_with(strtolower($webUrl), '.xml') || str_contains($webUrl, 'sitemap');

        // Build Firecrawl options - simple config matching playground
        $options = [
            'url' => $webUrl,
            'formats' => ['markdown', 'html'],
            'waitFor' => 5000, // Increased to 5 seconds for JavaScript-heavy sites
            'timeout' => 60000, // 60 second timeout for the scrape operation
            'onlyMainContent' => false, // Get all content, not just main content
        ];

        // Bypass cache for Vinted pages to get fresh results (maxAge: 0 = no cache)
        if (str_contains($webUrl, 'vinted.')) {
            $options['maxAge'] = 0;
        }

        // Use premium proxy for sites with anti-bot protection (like idealista.com)
        if (str_contains($webUrl, 'idealista.com')) {
            $options['actions'] = [
                ['type' => 'wait', 'milliseconds' => 3000]
            ];
            // Note: Firecrawl may require premium plan for advanced proxy options
            // Check your Firecrawl plan and API documentation for available proxy tiers
        }

        // For XML files, get raw content
        if ($isXmlUrl) {
            $options['waitFor'] = 0;
            $options['formats'] = ['rawHtml'];
        }

        try {
            Log::info('Firecrawl request options', [
                'url' => $webUrl,
                'options' => $options,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120) // 2 minute timeout for large sitemaps
            ->post($this->apiUrl, $options);

            if (!$response->successful()) {
                Log::error('Firecrawl API request failed', [
                    'url' => $webUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new FirecrawlApiException(
                    $webUrl
                );
            }

            $data = $response->json();

            if (!isset($data['success']) || !$data['success']) {
                Log::error('Firecrawl API returned unsuccessful response', [
                    'url' => $webUrl,
                    'response' => $data,
                ]);
                throw new FirecrawlApiException(
                    $webUrl
                );
            }

            // Check if this is an XML file (like a sitemap)
            $contentType = $data['data']['metadata']['contentType'] ?? '';
            $isXml = str_contains($contentType, 'xml') || str_ends_with($webUrl, '.xml') || str_contains($webUrl, 'sitemap');
            
            if ($isXml) {
                // For XML files, try to extract the actual XML content
                // When we request 'rawHtml' format for XML, it should come in that field
                $rawHtml = $data['data']['rawHtml'] ?? $data['data']['html'] ?? '';
                
                if (!empty($rawHtml)) {
                    // Clean up the HTML wrapping that Firecrawl adds to XML
                    // Remove the HTML comment wrapper around XML declaration
                    $xml = preg_replace('/<!--\?xml[^>]+\?-->/', '<?xml version="1.0" encoding="UTF-8"?>', (string) $rawHtml);
                    // Remove HTML tags
                    $xml = preg_replace('/<\/?html[^>]*>/', '', (string) $xml);
                    $xml = preg_replace('/<\/?head[^>]*>/', '', (string) $xml);
                    $xml = preg_replace('/<\/?body[^>]*>/', '', (string) $xml);
                    
                    return trim((string) $xml);
                }
            }
            
            // For regular HTML pages
            $html = $data['data']['html'] ?? '';

            if (empty($html)) {
                throw new FirecrawlApiException(
                    $webUrl
                );
            }

            // Debug logging for small HTML responses (likely blocked or insufficient content)
            if (strlen($html) < 10000) {
                Log::warning('Firecrawl returned small HTML response', [
                    'url' => $webUrl,
                    'html_length' => strlen($html),
                    'html_preview' => substr($html, 0, 800),
                    'firecrawl_metadata' => $data['data']['metadata'] ?? null
                ]);
            }

            return $html;
        
        } catch (FirecrawlApiException $exception) {
            Log::error("Error fetching html content for url {$webUrl} (Firecrawl): " . $exception->getMessage());
            return null;

        } catch (Exception $exception) {
            Log::error("Unexpected error fetching html content for url {$webUrl}: " . $exception->getMessage());
            return null;
        }
    }
}