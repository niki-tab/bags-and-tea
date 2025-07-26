<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use Src\Products\Product\Domain\ProductRepository;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

final class ProductSearcher
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    public function search(string $query, string $locale = 'en', int $limit = 10): array
    {
        // Sanitize query
        $query = trim($query);
        if (strlen($query) < 1) {
            return [];
        }

        // Build search with fuzzy matching and typo tolerance
        $results = $this->performFuzzySearch($query, $locale, $limit);
        
        return $this->formatResults($results, $locale);
    }

    public function getSuggestions(string $query, string $locale = 'en', int $limit = 5): array
    {
        $query = trim($query);
        if (strlen($query) < 1) {
            return [];
        }

        // Get quick suggestions based on name prefixes
        $suggestions = ProductEloquentModel::query()
            ->where('is_hidden', false)
            ->where(function ($q) use ($query, $locale) {
                $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', [$query . '%'])
                  ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['% ' . $query . '%']);
            })
            ->limit($limit)
            ->get()
            ->map(function ($product) use ($locale) {
                return $product->getTranslation('name', $locale);
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return $suggestions;
    }

    private function performFuzzySearch(string $query, string $locale, int $limit): array
    {
        $queryBuilder = ProductEloquentModel::query()
            ->with(['brand', 'vendor', 'quality', 'categories', 'attributes', 'primaryImage'])
            ->where('is_hidden', false);

        // Multi-field search with different weights
        $queryBuilder->where(function ($q) use ($query, $locale) {
            // Exact matches (highest priority) - using raw JSON queries for better compatibility
            $q->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $query . '%'])
              ->orWhere('sku', 'LIKE', '%' . $query . '%');
              
            // Description search - only if description exists
            if ($locale === 'en' || $locale === 'es') {
                $q->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(description_1, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $query . '%']);
            }

            // Brand search
            $q->orWhereHas('brand', function ($brandQuery) use ($query, $locale) {
                $brandQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $query . '%']);
            });

            // Category search
            $q->orWhereHas('categories', function ($catQuery) use ($query, $locale) {
                $catQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $query . '%']);
            });

            // Attribute search
            $q->orWhereHas('attributes', function ($attrQuery) use ($query, $locale) {
                $attrQuery->whereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.'. $locale .'"))) LIKE LOWER(?)', ['%' . $query . '%']);
            });

            // Fuzzy matching using SOUNDEX for typo tolerance
            $q->orWhereRaw("SOUNDEX(JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}'))) = SOUNDEX(?)", [$query]);
        });

        // Order by relevance (exact name matches first)
        $queryBuilder->orderByRaw("
            CASE 
                WHEN JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}')) = ? THEN 1
                WHEN JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}')) LIKE ? THEN 2  
                WHEN JSON_UNQUOTE(JSON_EXTRACT(`name`, '$.{$locale}')) LIKE ? THEN 3
                WHEN `sku` LIKE ? THEN 4
                ELSE 5
            END
        ", [$query, $query . '%', '%' . $query . '%', '%' . $query . '%']);

        return $queryBuilder->limit($limit)->get()->toArray();
    }

    private function formatResults(array $results, string $locale): array
    {
        return array_map(function ($product) use ($locale) {
            // Handle image URL properly - same logic as shop page
            $imageUrl = null;
            if (isset($product['primary_image']['file_path'])) {
                $filePath = $product['primary_image']['file_path'];
                // Check if it's an R2 URL (full URL) or local storage path
                if (str_starts_with($filePath, 'https://') || str_contains($filePath, 'r2.cloudflarestorage.com')) {
                    $imageUrl = $filePath; // Use R2 URL directly
                } else {
                    $imageUrl = asset($filePath); // Use asset() for local storage
                }
            }

            return [
                'id' => $product['id'],
                'name' => $product['name'][$locale] ?? $product['name']['en'] ?? '',
                'slug' => $product['slug'][$locale] ?? $product['slug']['en'] ?? '',
                'sku' => $product['sku'],
                'price' => $product['price'],
                'discounted_price' => $product['discounted_price'],
                'brand' => $product['brand']['name'][$locale] ?? $product['brand']['name']['en'] ?? '',
                'image' => $imageUrl,
                'url' => $this->buildProductUrl($product['slug'][$locale] ?? $product['slug']['en'] ?? '', $locale)
            ];
        }, $results);
    }

    private function buildProductUrl(string $slug, string $locale): string
    {
        $routeName = $locale === 'es' ? 'product.show.es' : 'product.show.en';
        return route($routeName, ['locale' => $locale, 'productSlug' => $slug]);
    }
}