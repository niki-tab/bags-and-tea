<?php
declare(strict_types=1);

namespace Src\ThirdPartyServices\OpenAi\Application;

use OpenAI\Client;
use Exception;
use Illuminate\Support\Facades\Log;

//instalar paquete OpenAI
//instalar Crawler

final readonly class ParseListingDetails
{
    public function __construct(private Client $openai)
    {
    }

    public function __invoke(string $htmlContent, string $jobUrl): array
    {
        try {
            Log::info('Starting business listing detail parsing', [
                'url' => $jobUrl,
                'html_length' => strlen($htmlContent)
            ]);

            // Sanitize HTML content
            $sanitizedHtml = $this->sanitizeHtmlContent($htmlContent);

            Log::info('HTML content sanitized', [
                'original_length' => strlen($htmlContent),
                'sanitized_length' => strlen($sanitizedHtml)
            ]);

            // AI prompt for business listing extraction with validation
            $systemPrompt = 'You are analyzing an HTML page to determine if it contains a listing for a SPECIFIC business for sale, transfer, or rent (traspaso).

CRITICAL: Set is_valid_business_listing to TRUE only if ALL conditions are met:
1. The page describes ONE specific business for sale, transfer, or rent (not multiple listings)
2. The page contains detailed information about THIS specific business
3. This is an actual business listing intended to be sold, transferred, or rented

Set is_valid_business_listing to FALSE if the page is:
- A listings page showing multiple businesses
- A search results page with multiple businesses
- A page that does not describe a business for sale, transfer, or rent
- An error page, 404 page, or page indicating the listing no longer exists

Extract the following information and return it as JSON. Use null for any field not found or unclear.

CONTACT INFORMATION EXTRACTION RULES:
1. Look for SELLER information first (owner or representative of the business)
2. If no seller is found, look for a contact person
3. Extract both name and email for the primary contact

IMAGE EXTRACTION RULES:
1. Extract ALL photo URLs that show the actual business being sold/transferred (interior, exterior, products, etc.)
2. ONLY include images that are relevant to the business itself
3. DO NOT include portal logos, navigation icons, or other website interface elements
4. Return full URLs for all business images as an array
5. If no business images are found, return an empty array []

Required fields:
- is_valid_business_listing: boolean - true if this is a valid single business listing, false otherwise
- business_type: type of business (e.g., restaurant, bar, retail store, salon, etc.)
- listing_title: title or name of the listing (null if not a valid listing)
- listing_description: full description of the business listing (null if not valid)
- location_country: country where the business is located
- location_region: region/province where the business is located
- location_city: city where the business is located
- location_postal_code: postal code of the business
- location_address_1: street name and number
- location_address_2: additional address information not covered by other fields
- selling_price: the asking price for the business
- annual_revenue: annual revenue of the business (null if not found)
- monthly_rent: monthly rent the business pays (null if not found)
- general_costs: any other costs besides rent and selling price (null if not found)
- seller_email: primary contact email
- seller_phone: primary contact phone number
- seller_name: primary contact name
- posted_at: when the listing was posted (YYYY-MM-DD format if possible)
- images: array of full URLs to business photos (empty array if none found)

Return only valid JSON with these exact field names.';

            // JSON schema for structured output
            $jsonSchema = [
                'name' => 'business_listing_details',
                'description' => 'Extract detailed business listing information',
                'strict' => true,
                'schema' => [
                    'type' => 'object',
                    'properties' => [
                        'is_valid_business_listing' => ['type' => 'boolean'],
                        'business_type' => ['type' => ['string', 'null']],
                        'listing_title' => ['type' => ['string', 'null']],
                        'listing_description' => ['type' => ['string', 'null']],
                        'location_country' => ['type' => ['string', 'null']],
                        'location_region' => ['type' => ['string', 'null']],
                        'location_city' => ['type' => ['string', 'null']],
                        'location_postal_code' => ['type' => ['string', 'null']],
                        'location_address_1' => ['type' => ['string', 'null']],
                        'location_address_2' => ['type' => ['string', 'null']],
                        'selling_price' => ['type' => ['string', 'null']],
                        'annual_revenue' => ['type' => ['string', 'null']],
                        'monthly_rent' => ['type' => ['string', 'null']],
                        'general_costs' => ['type' => ['string', 'null']],
                        'seller_email' => ['type' => ['string', 'null']],
                        'seller_phone' => ['type' => ['string', 'null']],
                        'seller_name' => ['type' => ['string', 'null']],
                        'posted_at' => ['type' => ['string', 'null']],
                        'images' => [
                            'type' => 'array',
                            'items' => ['type' => 'string']
                        ]
                    ],
                    'required' => ['is_valid_business_listing', 'business_type', 'listing_title', 'listing_description', 'location_country', 'location_region', 'location_city', 'location_postal_code', 'location_address_1', 'location_address_2', 'selling_price', 'annual_revenue', 'monthly_rent', 'general_costs', 'seller_email', 'seller_phone','seller_name', 'posted_at', 'images'],
                    'additionalProperties' => false
                ]
            ];

            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $sanitizedHtml]
            ];

            Log::info('Sending request to OpenAI', [
                'model' => 'gpt-4o-mini',
                'content_length' => strlen($sanitizedHtml)
            ]);

            // Make the API call
            $response = $this->openai->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'temperature' => 0.2,
                'max_tokens' => 3000,
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => $jsonSchema
                ]
            ]);

            Log::info('Received response from OpenAI', [
                'finish_reason' => $response->choices[0]->finishReason ?? 'unknown',
                'usage' => [
                    'prompt_tokens' => $response->usage->promptTokens ?? 0,
                    'completion_tokens' => $response->usage->completionTokens ?? 0,
                    'total_tokens' => $response->usage->totalTokens ?? 0
                ]
            ]);

            // Parse the response
            $jsonString = $response->choices[0]->message->content ?? '{}';
            $parsedData = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON parsing failed', [
                    'error' => json_last_error_msg(),
                    'response' => substr($jsonString, 0, 500)
                ]);
                throw new \RuntimeException('Invalid JSON from OpenAI: ' . json_last_error_msg());
            }

            Log::info('Successfully parsed business listing details', [
                'url' => $jobUrl,
                'is_valid_business_listing' => $parsedData['is_valid_business_listing'] ?? false,
                'extracted_fields' => array_keys($parsedData),
                'has_title' => !empty($parsedData['listing_title']),
                'has_description' => !empty($parsedData['listing_description']),
                'has_location' => !empty($parsedData['location_city'])
            ]);

            return $parsedData;

        } catch (Exception $e) {
            Log::error('Failed to parse business listing details', [
                'url' => $jobUrl,
                'error' => $e->getMessage()
            ]);
            throw new Exception("Failed to parse business listing details: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function sanitizeHtmlContent(string $htmlContent): string
    {
        // Remove script and style tags
        $htmlContent = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $htmlContent);
        $htmlContent = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/i', '', (string) $htmlContent);
        
        // Remove HTML comments
        $htmlContent = preg_replace('/<!--.*?-->/s', '', (string) $htmlContent);
        
        // Remove control characters
        $htmlContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', (string) $htmlContent);
        
        // Trim whitespace
        $htmlContent = trim((string) $htmlContent);
        
        // Limit content size if too large (200KB max)
        if (strlen($htmlContent) > 200000) {
            Log::warning('HTML content truncated for AI processing', [
                'original_length' => strlen($htmlContent),
                'truncated_to' => 200000
            ]);
            $htmlContent = substr($htmlContent, 0, 200000);
        }
        
        return $htmlContent;
    }
}