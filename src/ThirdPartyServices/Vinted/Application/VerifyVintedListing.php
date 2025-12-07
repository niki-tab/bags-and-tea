<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Vinted\Application;

use OpenAI\Client;
use Illuminate\Support\Facades\Log;

/**
 * Uses AI to verify if a Vinted listing is actually the product we're looking for
 */
final class VerifyVintedListing
{
    public function __construct(private Client $openai)
    {
    }

    /**
     * Verify if the listing matches the expected product and extract upload date
     *
     * @param string $expectedProduct The product we're looking for (e.g., "Louis Vuitton Neverfull MM bag")
     * @param string $listingTitle The title of the listing
     * @param string|null $listingDescription The description from the listing page
     * @param string|null $htmlContent The raw HTML content to extract upload date from
     * @return array ['is_verified' => bool, 'reason' => string, 'uploaded_text' => string|null]
     */
    public function __invoke(
        string $expectedProduct,
        string $listingTitle,
        ?string $listingDescription = null,
        ?string $htmlContent = null
    ): array {
        try {
            $prompt = $this->buildPrompt($expectedProduct, $listingTitle, $listingDescription, $htmlContent);

            $response = $this->openai->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 300,
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => $this->getJsonSchema(),
                ],
            ]);

            $jsonString = $response->choices[0]->message->content ?? '{"is_match": false, "reason": "Failed to analyze", "uploaded_text": null}';
            $result = json_decode($jsonString, true);

            Log::info('AI verification result', [
                'expected' => $expectedProduct,
                'title' => $listingTitle,
                'is_match' => $result['is_match'] ?? false,
                'reason' => $result['reason'] ?? 'Unknown',
                'uploaded_text' => $result['uploaded_text'] ?? null,
            ]);

            return [
                'is_verified' => $result['is_match'] ?? false,
                'reason' => $result['reason'] ?? 'Unknown',
                'uploaded_text' => $result['uploaded_text'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('AI verification failed', [
                'error' => $e->getMessage(),
                'expected' => $expectedProduct,
                'title' => $listingTitle,
            ]);

            // Return null for is_verified on error so it will be retried later
            return [
                'is_verified' => null,
                'reason' => 'Verification failed: ' . $e->getMessage(),
                'uploaded_text' => null,
            ];
        }
    }

    private function getSystemPrompt(): string
    {
        return <<<'PROMPT'
You are an expert at identifying luxury bags and accessories. Your job is to:

1. Verify if a Vinted listing actually matches the product being searched for
2. Extract the EXACT upload date text from the page content

For product verification, determine if the listing is THE ACTUAL BAG/PRODUCT or something else like:
- Accessories (pouches, organizers, boxes, dust bags)
- Similar but different products (shoes, wallets, clothes)
- Different bag models
- Counterfeit/replica indicators

Be strict: if there's any doubt, mark it as NOT a match.

For upload date extraction:
- Look for the text that appears AFTER "Subido" (Spanish) or "Uploaded"/"Added" (English)
- The upload date is usually something like "hace 2 meses", "hace 5 días", "2 months ago"
- DO NOT confuse with "Última visita" or "Last seen" - that's the seller's last connection, NOT the upload date
- Extract the EXACT text you find, do not make up or guess the date
- If you cannot find the upload date, return null
PROMPT;
    }

    private function buildPrompt(string $expectedProduct, string $title, ?string $description, ?string $htmlContent): string
    {
        $prompt = "Expected product: {$expectedProduct}\n\n";
        $prompt .= "Listing title: {$title}\n\n";

        if ($description) {
            // Truncate description if too long
            $desc = mb_strlen($description) > 1000 ? mb_substr($description, 0, 1000) . '...' : $description;
            $prompt .= "Listing description: {$desc}\n\n";
        }

        if ($htmlContent) {
            // Send full HTML content to AI (GPT-4o-mini can handle large context)
            $prompt .= "Page HTML content (find the upload date - look for 'Subido' or 'Uploaded', NOT 'Última visita'):\n{$htmlContent}\n\n";
        }

        $prompt .= "Please verify if this listing matches the expected product and extract the EXACT upload date from the HTML.";

        return $prompt;
    }

    private function getJsonSchema(): array
    {
        return [
            'name' => 'product_verification',
            'description' => 'Verify if a listing matches the expected product and extract upload date',
            'strict' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'is_match' => [
                        'type' => 'boolean',
                        'description' => 'True if the listing is the actual product (bag), false if it\'s an accessory or different product',
                    ],
                    'reason' => [
                        'type' => 'string',
                        'description' => 'Brief explanation of why it matches or doesn\'t match (max 50 words)',
                    ],
                    'uploaded_text' => [
                        'type' => ['string', 'null'],
                        'description' => 'The upload date text (e.g., "Subido hace 3 semanas", "Added 2 days ago"). Extract from page content. Do NOT use last visit/connection time.',
                    ],
                ],
                'required' => ['is_match', 'reason', 'uploaded_text'],
                'additionalProperties' => false,
            ],
        ];
    }
}
