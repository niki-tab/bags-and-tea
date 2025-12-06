<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Vinted\Application;

use OpenAI\Client;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Phase 1: Uses AI to extract listing data from Vinted search results HTML
 */
final readonly class ParseVintedSearchResults
{
    public function __construct(private Client $openai)
    {
    }

    public function __invoke(string $htmlContent, string $searchUrl): array
    {
        try {
            Log::info('Starting Vinted search results parsing', [
                'url' => $searchUrl,
                'html_length' => strlen($htmlContent)
            ]);

            // Fast regex-based extraction (no AI needed)
            $listings = $this->extractListingsWithRegex($htmlContent);

            Log::info('Successfully parsed Vinted search results', [
                'url' => $searchUrl,
                'total_listings_found' => count($listings)
            ]);

            return ['listings' => $listings];

        } catch (\Exception $e) {
            Log::error('Failed to parse Vinted search results', [
                'url' => $searchUrl,
                'error' => $e->getMessage()
            ]);
            throw new \Exception("Failed to parse Vinted search results: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function extractListingsWithRegex(string $html): array
    {
        $listings = [];
        $seenIds = [];

        // Find all item links with their IDs
        // Pattern: /items/1234567-slug
        preg_match_all(
            '/href="(https?:\/\/www\.vinted\.[a-z]+\/items\/(\d+)-[^"]+)"/i',
            $html,
            $urlMatches,
            PREG_SET_ORDER
        );

        foreach ($urlMatches as $match) {
            $url = $match[1];
            $itemId = $match[2];

            // Skip duplicates
            if (in_array($itemId, $seenIds)) {
                continue;
            }
            $seenIds[] = $itemId;

            // Find the context around this URL to extract price, title, image
            $pos = strpos($html, $url);
            $contextStart = max(0, $pos - 2000);
            $contextEnd = min(strlen($html), $pos + 2000);
            $context = substr($html, $contextStart, $contextEnd - $contextStart);

            // Extract price from multiple possible formats
            $price = null;

            // Try to get price from the title attribute first (most reliable)
            // Format in title: "Product name, marca: Brand, estado: Condition, 350,00 €"
            if (preg_match('/(\d{1,3}(?:[.\s]?\d{3})*)[,.](\d{2})\s*€/', $context, $priceMatch)) {
                // Handle thousands separator: "1.350,00 €" or "1 350,00 €"
                $wholePart = preg_replace('/[.\s]/', '', $priceMatch[1]);
                $price = floatval($wholePart . '.' . $priceMatch[2]);
            } elseif (preg_match('/(\d+)\s*€/', $context, $priceMatch)) {
                // Simple format: "350 €" (no decimals)
                $price = floatval($priceMatch[1]);
            } elseif (preg_match('/"price":\s*(\d+\.?\d*)/', $context, $priceMatch)) {
                // JSON format: "price": 350.00
                $price = floatval($priceMatch[1]);
            } elseif (preg_match('/"total_item_price":\s*"(\d+\.?\d*)"/', $context, $priceMatch)) {
                // Alternative JSON format
                $price = floatval($priceMatch[1]);
            }

            // Extract title from the link's title attribute
            // Format: "Product name, marca: Brand, estado: Condition, price..."
            $title = null;
            if (preg_match('/title="([^"]+)"[^>]*data-testid="product-item-id-' . preg_quote($itemId, '/') . '/', $context, $titleMatch)) {
                $fullTitle = html_entity_decode($titleMatch[1], ENT_QUOTES, 'UTF-8');
                // Extract just the product name (before ", marca:")
                if (preg_match('/^(.+?),\s*marca:/i', $fullTitle, $nameMatch)) {
                    $title = trim($nameMatch[1]);
                } else {
                    $title = $fullTitle;
                }
            } elseif (preg_match('/href="[^"]*' . preg_quote($itemId, '/') . '[^"]*"[^>]*title="([^"]+)"/', $context, $titleMatch)) {
                $fullTitle = html_entity_decode($titleMatch[1], ENT_QUOTES, 'UTF-8');
                if (preg_match('/^(.+?),\s*marca:/i', $fullTitle, $nameMatch)) {
                    $title = trim($nameMatch[1]);
                } else {
                    $title = $fullTitle;
                }
            }

            // Extract image URL
            $imageUrl = null;
            if (preg_match('/(https:\/\/images\d*\.vinted\.net\/[^"]+\.(?:jpg|jpeg|png|webp))/i', $context, $imgMatch)) {
                $imageUrl = $imgMatch[1];
            }

            // Extract brand
            $brand = null;
            if (preg_match('/Louis\s*Vuitton/i', $context)) {
                $brand = 'Louis Vuitton';
            } elseif (preg_match('/Chanel/i', $context)) {
                $brand = 'Chanel';
            } elseif (preg_match('/Gucci/i', $context)) {
                $brand = 'Gucci';
            } elseif (preg_match('/Hermès|Hermes/i', $context)) {
                $brand = 'Hermès';
            }

            $listings[] = [
                'title' => $title,
                'price' => $price,
                'currency' => 'EUR',
                'url' => $url,
                'vinted_item_id' => $itemId,
                'main_image_url' => $imageUrl,
                'brand' => $brand,
                'size' => null,
            ];
        }

        return $listings;
    }

    private function splitIntoChunks(string $html, int $maxChunkSize = 150000): array
    {
        $htmlLength = mb_strlen($html, 'UTF-8');

        // If small enough, return as single chunk
        if ($htmlLength <= $maxChunkSize) {
            return [$html];
        }

        $chunks = [];
        $position = 0;

        while ($position < $htmlLength) {
            $chunkEnd = min($position + $maxChunkSize, $htmlLength);

            // If not at the end, find a safe break point (space, tag boundary)
            if ($chunkEnd < $htmlLength) {
                // Look for a good break point in the last 1000 chars
                $searchStart = max($position, $chunkEnd - 1000);
                $searchArea = mb_substr($html, $searchStart, $chunkEnd - $searchStart, 'UTF-8');

                // Find last closing tag or space
                $lastTag = mb_strrpos($searchArea, '>', 0, 'UTF-8');
                $lastSpace = mb_strrpos($searchArea, ' ', 0, 'UTF-8');

                $breakPoint = max($lastTag, $lastSpace);
                if ($breakPoint !== false && $breakPoint > 0) {
                    $chunkEnd = $searchStart + $breakPoint + 1;
                }
            }

            $chunk = mb_substr($html, $position, $chunkEnd - $position, 'UTF-8');
            $chunks[] = $chunk;
            $position = $chunkEnd;
        }

        Log::info('Split HTML into chunks', [
            'total_chunks' => count($chunks),
            'original_size' => $htmlLength
        ]);

        return $chunks;
    }

    private function parseChunk(string $htmlChunk, string $searchUrl): array
    {
        $systemPrompt = 'You are analyzing an HTML chunk from Vinted (a second-hand marketplace) search results.

Extract ALL product listings visible in this HTML chunk. For each listing, extract:
- title: The product title/name
- price: The price as a number (without currency symbol)
- currency: The currency code (EUR, GBP, etc.)
- url: The full URL to the listing (starts with https://www.vinted.es/items/ or similar)
- vinted_item_id: The item ID from the URL (the numeric part after /items/)
- main_image_url: The main product image URL
- brand: The brand name if visible
- size: The size if visible

IMPORTANT RULES:
1. Extract ALL listings you can find in this HTML
2. The vinted_item_id is the numeric ID from URLs like /items/1234567-title
3. Price should be a number only (e.g., 450.00 not "450 €")
4. If a field is not visible, use null

Return the data as a JSON object with a "listings" array.';

        $jsonSchema = [
            'name' => 'vinted_search_results',
            'description' => 'Extract product listings from Vinted search results',
            'strict' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'listings' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'title' => ['type' => ['string', 'null']],
                                'price' => ['type' => ['number', 'null']],
                                'currency' => ['type' => ['string', 'null']],
                                'url' => ['type' => ['string', 'null']],
                                'vinted_item_id' => ['type' => ['string', 'null']],
                                'main_image_url' => ['type' => ['string', 'null']],
                                'brand' => ['type' => ['string', 'null']],
                                'size' => ['type' => ['string', 'null']],
                            ],
                            'required' => ['title', 'price', 'currency', 'url', 'vinted_item_id', 'main_image_url', 'brand', 'size'],
                            'additionalProperties' => false
                        ]
                    ]
                ],
                'required' => ['listings'],
                'additionalProperties' => false
            ]
        ];

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $htmlChunk]
        ];

        $response = $this->openai->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => $messages,
            'temperature' => 0.1,
            'max_tokens' => 16000,
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $jsonSchema
            ]
        ]);

        Log::info('Chunk parsed by OpenAI', [
            'finish_reason' => $response->choices[0]->finishReason ?? 'unknown',
            'tokens_used' => $response->usage->totalTokens ?? 0
        ]);

        $jsonString = $response->choices[0]->message->content ?? '{"listings": []}';
        $parsedData = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON parsing failed for chunk', ['error' => json_last_error_msg()]);
            return [];
        }

        return $parsedData['listings'] ?? [];
    }

    private function sanitizeHtmlContent(string $htmlContent): string
    {
        // Fix UTF-8 encoding issues
        $htmlContent = mb_convert_encoding($htmlContent, 'UTF-8', 'UTF-8');

        // Remove script and style tags
        $htmlContent = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $htmlContent);
        $htmlContent = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/i', '', (string) $htmlContent);

        // Remove HTML comments
        $htmlContent = preg_replace('/<!--.*?-->/s', '', (string) $htmlContent);

        // Remove control characters and invalid UTF-8
        $htmlContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string) $htmlContent);

        // Remove any remaining invalid UTF-8 sequences
        $htmlContent = iconv('UTF-8', 'UTF-8//IGNORE', (string) $htmlContent);

        // Trim whitespace
        $htmlContent = trim((string) $htmlContent);

        return $htmlContent;
    }
}
