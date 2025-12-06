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
     * Verify if the listing matches the expected product
     *
     * @param string $expectedProduct The product we're looking for (e.g., "Louis Vuitton Neverfull MM bag")
     * @param string $listingTitle The title of the listing
     * @param string|null $listingDescription The description from the listing page
     * @param array $images Array of image URLs (for context, not analyzed directly)
     * @return array ['is_verified' => bool, 'reason' => string]
     */
    public function __invoke(
        string $expectedProduct,
        string $listingTitle,
        ?string $listingDescription = null,
        array $images = []
    ): array {
        try {
            $prompt = $this->buildPrompt($expectedProduct, $listingTitle, $listingDescription);

            $response = $this->openai->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $this->getSystemPrompt()],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 200,
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => $this->getJsonSchema(),
                ],
            ]);

            $jsonString = $response->choices[0]->message->content ?? '{"is_match": false, "reason": "Failed to analyze"}';
            $result = json_decode($jsonString, true);

            Log::info('AI verification result', [
                'expected' => $expectedProduct,
                'title' => $listingTitle,
                'is_match' => $result['is_match'] ?? false,
                'reason' => $result['reason'] ?? 'Unknown',
            ]);

            return [
                'is_verified' => $result['is_match'] ?? false,
                'reason' => $result['reason'] ?? 'Unknown',
            ];

        } catch (\Exception $e) {
            Log::error('AI verification failed', [
                'error' => $e->getMessage(),
                'expected' => $expectedProduct,
                'title' => $listingTitle,
            ]);

            // Default to not verified on error
            return [
                'is_verified' => false,
                'reason' => 'Verification failed: ' . $e->getMessage(),
            ];
        }
    }

    private function getSystemPrompt(): string
    {
        return <<<'PROMPT'
You are an expert at identifying luxury bags and accessories. Your job is to verify if a Vinted listing actually matches the product being searched for.

You must determine if the listing is THE ACTUAL BAG/PRODUCT or something else like:
- Accessories (pouches, organizers, boxes, dust bags)
- Similar but different products (shoes, wallets, clothes)
- Different bag models
- Counterfeit/replica indicators

Be strict: if there's any doubt, mark it as NOT a match.
PROMPT;
    }

    private function buildPrompt(string $expectedProduct, string $title, ?string $description): string
    {
        $prompt = "Expected product: {$expectedProduct}\n\n";
        $prompt .= "Listing title: {$title}\n\n";

        if ($description) {
            // Truncate description if too long
            $desc = mb_strlen($description) > 1000 ? mb_substr($description, 0, 1000) . '...' : $description;
            $prompt .= "Listing description: {$desc}\n\n";
        }

        $prompt .= "Is this listing actually the expected product (the bag itself, not accessories)?";

        return $prompt;
    }

    private function getJsonSchema(): array
    {
        return [
            'name' => 'product_verification',
            'description' => 'Verify if a listing matches the expected product',
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
                ],
                'required' => ['is_match', 'reason'],
                'additionalProperties' => false,
            ],
        ];
    }
}
