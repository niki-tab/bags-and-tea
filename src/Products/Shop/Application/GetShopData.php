<?php

declare(strict_types=1);

namespace Src\Products\Shop\Application;

use Src\Attributes\Domain\AttributeRepository;
use Src\Categories\Domain\CategoryRepository;
use Src\Products\Brands\Domain\BrandRepository;
use Src\Products\Product\Domain\ProductRepository;
use Src\Products\Quality\Domain\QualityRepository;
use Src\Products\Shop\Domain\ShopFilterRepository;
use Src\Shared\Domain\Criteria\Criteria;
use Src\Shared\Domain\Criteria\Filter;
use Src\Shared\Domain\Criteria\FilterField;
use Src\Shared\Domain\Criteria\FilterOperator;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\FilterValue;
use Src\Shared\Domain\Criteria\Order;

final class GetShopData
{
    public function __construct(
        private ProductRepository $productRepository,
        private ShopFilterRepository $shopFilterRepository,
        private BrandRepository $brandRepository,
        private CategoryRepository $categoryRepository,
        private AttributeRepository $attributeRepository,
        private QualityRepository $qualityRepository
    ) {}

    public function execute(array $appliedFilters = [], string $sortBy = '', ?string $categorySlug = null, ?int $offset = null, ?int $limit = 50, ?string $searchQuery = null): array
    {
        // Get active shop filters configuration
        $activeFilters = $this->shopFilterRepository->findActive();

        // Get filter options for each active filter
        $filterOptions = [];
        foreach ($activeFilters as $filter) {
            // For category filters, use the filter_slug from config if available, otherwise use type
            $filterKey = $this->getFilterKey($filter);
            $filterOptions[$filterKey] = $this->getFilterOptions($filter);
        }

        // Get filtered products
        $products = $this->getFilteredProducts($appliedFilters, $sortBy, $categorySlug, $offset, $limit, $searchQuery);

        // Get total count for pagination (without limit/offset)
        $totalCount = count($this->getFilteredProducts($appliedFilters, $sortBy, $categorySlug, null, null, $searchQuery));

        return [
            'filters' => $activeFilters,
            'filterOptions' => $filterOptions,
            'products' => $products,
            'totalCount' => $totalCount,
            'appliedFilters' => $appliedFilters,
            'categorySlug' => $categorySlug,
        ];
    }

    private function getFilterOptions($filter): array
    {
        $options = [];

        switch ($filter->type) {
            case 'brand':
                $options = $this->brandRepository->findActive();
                break;

            case 'category':
                $options = $this->getCategoryFilterOptions($filter);
                break;

            case 'attribute':
                $options = $this->getAttributeFilterOptions($filter);
                break;

            case 'quality':
                $options = $this->qualityRepository->findAll();
                break;

            case 'price':
                return $this->getPriceRanges(); // Price ranges don't need alphabetical sorting

            default:
                return [];
        }

        // Sort options alphabetically by name (supporting translations)
        return $this->sortOptionsAlphabetically($options);
    }

    private function getFilteredProducts(array $appliedFilters, string $sortBy = '', ?string $categorySlug = null, ?int $offset = null, ?int $limit = 50, ?string $searchQuery = null): array
    {
        $filters = [];
        
        // Get active filters to determine filter types dynamically
        $activeFilters = $this->shopFilterRepository->findActive();
        $categoryFilterSlugs = [];
        $attributeFilterSlugs = [];
        
        // Build a map of category and attribute filter slugs for dynamic handling
        foreach ($activeFilters as $activeFilter) {
            if ($activeFilter->type === 'category' && !empty($activeFilter->config)) {
                $config = is_array($activeFilter->config) ? $activeFilter->config : json_decode($activeFilter->config, true);
                if (isset($config['filter_slug'])) {
                    $categoryFilterSlugs[] = $config['filter_slug'];
                }
            }
            
            if ($activeFilter->type === 'attribute' && !empty($activeFilter->config)) {
                $config = is_array($activeFilter->config) ? $activeFilter->config : json_decode($activeFilter->config, true);
                if (isset($config['filter_slug'])) {
                    $attributeFilterSlugs[] = $config['filter_slug'];
                }
            }
        }

        foreach ($appliedFilters as $filterType => $filterValues) {
            if (empty($filterValues)) {
                continue;
            }

            switch ($filterType) {
                case 'brands':
                    if (! empty($filterValues)) {
                        // Filter values should already be IDs from Livewire component
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('brand_id'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'categories':
                    if (! empty($filterValues)) {
                        // Filter values should already be IDs from Livewire component
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('categories'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'attributes':
                    if (! empty($filterValues)) {
                        // Filter values should already be IDs from Livewire component
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('attributes'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'qualities':
                    if (! empty($filterValues)) {
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('quality_id'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'priceRanges':
                    if (! empty($filterValues) && is_array($filterValues)) {
                        // Create a single filter with comma-separated values for the repository to handle
                        $filters[] = new Filter(
                            new FilterField('price_ranges'),
                            new FilterOperator('='),
                            new FilterValue(implode(',', $filterValues))
                        );
                    }
                    break;

                case 'urlBasedCategories':
                    // URL-based category filtering (doesn't pre-select filters)
                    if (! empty($filterValues)) {
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('categories'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'urlBasedAttributes':
                    // URL-based attribute filtering (doesn't pre-select filters)
                    if (! empty($filterValues)) {
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('attributes'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'urlBasedBrands':
                    // URL-based brand filtering (doesn't pre-select filters)
                    if (! empty($filterValues)) {
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('brand_id'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;
                    
                default:
                    // Handle dynamic category filters based on filter_slug
                    if (in_array($filterType, $categoryFilterSlugs) && !empty($filterValues)) {
                        // Convert category slugs to IDs
                        $categoryIds = $this->convertCategorySlugsToIds($filterValues);
                        if (!empty($categoryIds)) {
                            $filterValueString = is_array($categoryIds) ? implode(',', $categoryIds) : $categoryIds;
                            $filters[] = new Filter(
                                new FilterField('categories'),
                                new FilterOperator('in'),
                                new FilterValue($filterValueString)
                            );
                        }
                    }
                    
                    // Handle dynamic attribute filters based on filter_slug
                    if (in_array($filterType, $attributeFilterSlugs) && !empty($filterValues)) {
                        // Convert attribute slugs to IDs
                        $attributeIds = $this->convertAttributeSlugsToIds($filterValues);
                        if (!empty($attributeIds)) {
                            $filterValueString = is_array($attributeIds) ? implode(',', $attributeIds) : $attributeIds;
                            $filters[] = new Filter(
                                new FilterField('attributes'),
                                new FilterOperator('in'),
                                new FilterValue($filterValueString)
                            );
                        }
                    }
                    break;
            }
        }

        // Add search functionality if search query is provided
        if (!empty($searchQuery) && strlen(trim($searchQuery)) >= 2) {
            $trimmedQuery = trim($searchQuery);
            $filters[] = new Filter(
                new FilterField('search'),
                new FilterOperator('like'),
                new FilterValue($trimmedQuery)
            );
        }

        // Create sorting order
        $order = $this->createOrder($sortBy);

        // If no filters are applied, get all products
        if (empty($filters)) {
            $criteria = new Criteria(
                new Filters([]),
                $order,
                $offset,
                $limit
            );
        } else {
            $criteria = new Criteria(
                new Filters($filters),
                $order,
                $offset,
                $limit
            );
        }

        return $this->productRepository->searchByCriteria($criteria);
    }

    private function createOrder(string $sortBy): Order
    {
        if (empty($sortBy)) {
            return Order::none();
        }

        switch ($sortBy) {
            case 'name_asc':
                return Order::fromValues('name', 'asc');
            case 'name_desc':
                return Order::fromValues('name', 'desc');
            case 'price_asc':
                return Order::fromValues('price', 'asc');
            case 'price_desc':
                return Order::fromValues('price', 'desc');
            default:
                return Order::none();
        }
    }

    private function getPriceRanges(): array
    {
        // Return predefined price ranges or calculate from products
        return [
            ['min' => 0, 'max' => 100, 'label' => '€0 - €100'],
            ['min' => 100, 'max' => 500, 'label' => '€100 - €500'],
            ['min' => 500, 'max' => 1000, 'label' => '€500 - €1,000'],
            ['min' => 1000, 'max' => 2000, 'label' => '€1,000 - €2,000'],
            ['min' => 2000, 'max' => null, 'label' => '€2,000+'],
        ];
    }

    private function createPriceRangeCondition(string $priceRange): array
    {
        switch ($priceRange) {
            case '0-100':
                return ['min' => 0, 'max' => 100];
            case '100-500':
                return ['min' => 100, 'max' => 500];
            case '500-1000':
                return ['min' => 500, 'max' => 1000];
            case '1000-2000':
                return ['min' => 1000, 'max' => 2000];
            case '2000+':
                return ['min' => 2000, 'max' => null];
            default:
                return ['min' => null, 'max' => null];
        }
    }

    private function getFilterKey($filter): string
    {
        // For category and attribute filters, check if there's a filter_slug in config
        if (($filter->type === 'category' || $filter->type === 'attribute') && ! empty($filter->config)) {
            $config = is_array($filter->config) ? $filter->config : json_decode($filter->config, true);
            if (isset($config['filter_slug'])) {
                return $config['filter_slug'];
            }
        }

        return $filter->type;
    }

    private function getCategoryFilterOptions($filter): array
    {
        // Get all active parent categories
        $categories = $this->categoryRepository->findActiveRoots();

        // If there's a filter_slug in config, filter by slug
        if (! empty($filter->config)) {
            $config = is_array($filter->config) ? $filter->config : json_decode($filter->config, true);
            if (isset($config['filter_slug'])) {
                $filterSlug = $config['filter_slug'];
                foreach ($categories as $category) {
                    $slugMatch = false;

                    // Use getTranslation method to handle both JSON and array slug formats
                    $currentLocale = app()->getLocale();
                    
                    // Check current locale first
                    if ($category->getTranslation('slug', $currentLocale) === $filterSlug) {
                        $slugMatch = true;
                    } else {
                        // Check all supported locales
                        foreach (['en', 'es'] as $locale) {
                            if ($category->getTranslation('slug', $locale) === $filterSlug) {
                                $slugMatch = true;
                                break;
                            }
                        }
                    }

                    if ($slugMatch) {
                        // Return the children of this category as objects
                        // Use direct query to ensure we get proper objects
                        $children = \Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel::where('parent_id', $category->id)
                            ->where('is_active', true)
                            ->orderBy('display_order')
                            ->get()
                            ->all();

                        return $children;
                    }
                }

                return [];
            }
        }

        // If no filter_slug, return all parent categories
        return $categories;
    }

    private function getAttributeFilterOptions($filter): array
    {
        // Get all active root attributes
        $attributes = $this->attributeRepository->findActiveRoots();

        // If there's a filter_slug in config, filter by slug
        if (! empty($filter->config)) {
            $config = is_array($filter->config) ? $filter->config : json_decode($filter->config, true);
            if (isset($config['filter_slug'])) {
                $filterSlug = $config['filter_slug'];
                foreach ($attributes as $attribute) {
                    $slugMatch = false;

                    // Use getTranslation method to handle both JSON and array slug formats
                    $currentLocale = app()->getLocale();
                    
                    // Check current locale first
                    if ($attribute->getTranslation('slug', $currentLocale) === $filterSlug) {
                        $slugMatch = true;
                    } else {
                        // Check all supported locales
                        foreach (['en', 'es'] as $locale) {
                            if ($attribute->getTranslation('slug', $locale) === $filterSlug) {
                                $slugMatch = true;
                                break;
                            }
                        }
                    }

                    if ($slugMatch) {
                        // Return the children of this attribute as objects
                        // Use direct query to ensure we get proper objects
                        $children = \Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel::where('parent_id', $attribute->id)
                            ->where('is_active', true)
                            ->orderBy('display_order')
                            ->get()
                            ->all();

                        return $children;
                    }
                }

                return [];
            }
        }

        // If no filter_slug, return all root attributes
        return $attributes;
    }

    private function sortOptionsAlphabetically(array $options): array
    {
        if (empty($options)) {
            return $options;
        }

        // Sort options alphabetically by their translated name
        usort($options, function ($a, $b) {
            $nameA = $this->getOptionName($a);
            $nameB = $this->getOptionName($b);

            return strcasecmp($nameA, $nameB);
        });

        return $options;
    }

    private function getOptionName($option): string
    {
        // Handle different object types and their naming conventions
        if (is_object($option)) {
            // Check if it has getTranslation method (for translatable models)
            if (method_exists($option, 'getTranslation')) {
                return $option->getTranslation('name', app()->getLocale()) ?? '';
            }

            // Check if it has a name property (direct name)
            if (property_exists($option, 'name')) {
                if (is_array($option->name)) {
                    // Handle JSON translations
                    $currentLocale = app()->getLocale();

                    return $option->name[$currentLocale] ?? $option->name['en'] ?? '';
                }

                return $option->name ?? '';
            }
        }

        // Handle array format
        if (is_array($option)) {
            if (isset($option['name'])) {
                if (is_array($option['name'])) {
                    // Handle JSON translations in array
                    $currentLocale = app()->getLocale();

                    return $option['name'][$currentLocale] ?? $option['name']['en'] ?? '';
                }

                return $option['name'] ?? '';
            }
        }

        return '';
    }

    private function convertAttributeSlugsToIds($slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        // Ensure we have an array
        $slugArray = is_array($slugs) ? $slugs : [$slugs];
        $attributeIds = [];
        
        $currentLocale = app()->getLocale();
        
        foreach ($slugArray as $slug) {
            // Try to find attribute by slug in current locale first, then fallback to English
            $attribute = \Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel::where("slug->{$currentLocale}", $slug)
                ->orWhere('slug->en', $slug)
                ->first();
                
            if ($attribute) {
                $attributeIds[] = $attribute->id;
            }
        }
        
        return $attributeIds;
    }

    private function convertCategorySlugsToIds($slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        // Ensure we have an array
        $slugArray = is_array($slugs) ? $slugs : [$slugs];
        $categoryIds = [];
        
        $currentLocale = app()->getLocale();
        
        foreach ($slugArray as $slug) {
            // Try to find category by slug in current locale first, then fallback to English
            $category = \Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel::where("slug->{$currentLocale}", $slug)
                ->orWhere('slug->en', $slug)
                ->first();
                
            if ($category) {
                $categoryIds[] = $category->id;
            }
        }
        
        return $categoryIds;
    }

    private function convertBrandSlugsToIds($slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        // Ensure we have an array
        $slugArray = is_array($slugs) ? $slugs : [$slugs];
        $brandIds = [];
        
        $currentLocale = app()->getLocale();
        
        foreach ($slugArray as $slug) {
            // Try to find brand by slug in current locale first, then fallback to English
            $brand = \Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel::where("slug->{$currentLocale}", $slug)
                ->orWhere('slug->en', $slug)
                ->first();
                
            if ($brand) {
                $brandIds[] = $brand->id;
            }
        }
        
        return $brandIds;
    }
}
