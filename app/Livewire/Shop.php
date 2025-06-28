<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;
use Src\Attributes\Infrastructure\EloquentAttributeRepository;
use Src\Categories\Infrastructure\EloquentCategoryRepository;
use Src\Products\Brands\Infrastructure\EloquentBrandRepository;
use Src\Products\Product\Infrastructure\EloquentProductRepository;
use Src\Products\Quality\Infrastructure\EloquentQualityRepository;
use Src\Products\Shop\Application\GetShopData;
use Src\Products\Shop\Infrastructure\EloquentShopFilterRepository;

class Shop extends Component
{
    public $products = [];

    public $filters = [];

    public $filterOptions = [];

    public $appliedFilters = [];

    // URL-based filtering
    public $categorySlug = null;

    public $urlBasedFilters = [];

    // Brand-based content
    public $brandData = null;

    public $pageTitle = '';

    public $pageDescription = '';

    public $pageDescription2 = '';

    // Dynamic filter state - stores all filter selections
    public $selectedFilters = [];

    #[Url(as: 'filters')]
    public $urlFilters = '';

    #[Url(as: 'sort')]
    public $selectedSortBy = '';

    public $priceRange = ['min' => null, 'max' => null];

    // UI state
    public $loading = false;

    public function mount($categorySlug = null)
    {
        // Store the category slug for URL-based filtering
        $this->categorySlug = $categorySlug;

        // Set up URL-based filters if categorySlug is provided
        if ($this->categorySlug) {
            $this->setupUrlBasedFilters();
        }

        // Set default content if no specific content was set (no brand, category, or attribute match)
        if (empty($this->pageTitle)) {
            $this->setBrandData(null, app()->getLocale());
        }

        // Parse URL parameters to internal arrays
        $this->parseUrlParameters();

        // Parse price ranges if they come from URL
        if (! empty($this->selectedPriceRange)) {
            $this->parsePriceRanges($this->selectedPriceRange);
        }

        $this->loadShopData();
    }

    public function updatedSelectedFilters($value)
    {
        // Handle price range parsing if price filter was updated
        if (isset($this->selectedFilters['price'])) {
            $this->parsePriceRanges($this->selectedFilters['price']);
        }
        
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function updatedSelectedSortBy($value)
    {
        $this->loadShopData();
    }

    public function updatedPriceRange()
    {
        $this->loadShopData();
    }

    public function clearFilters()
    {
        $this->selectedFilters = [];
        $this->selectedSortBy = '';
        $this->priceRange = ['min' => null, 'max' => null];
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function removeFilter($type, $value)
    {
        if (isset($this->selectedFilters[$type])) {
            $this->selectedFilters[$type] = array_values(array_diff($this->selectedFilters[$type], [$value]));
            
            // Clean up empty filter arrays
            if (empty($this->selectedFilters[$type])) {
                unset($this->selectedFilters[$type]);
            }
            
            // Handle price range parsing
            if ($type === 'price' && isset($this->selectedFilters['price'])) {
                $this->parsePriceRanges($this->selectedFilters['price']);
            }
        }
        
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function toggleFilter($type, $value)
    {
        // Initialize filter array if it doesn't exist
        if (!isset($this->selectedFilters[$type])) {
            $this->selectedFilters[$type] = [];
        }

        // Check if the value is already selected
        if (in_array($value, $this->selectedFilters[$type])) {
            // Remove it if it's already selected
            $this->selectedFilters[$type] = array_values(array_diff($this->selectedFilters[$type], [$value]));
            
            // Clean up empty filter arrays
            if (empty($this->selectedFilters[$type])) {
                unset($this->selectedFilters[$type]);
            }
        } else {
            // Add it if it's not selected
            $this->selectedFilters[$type][] = $value;
        }

        // Handle price range parsing
        if ($type === 'price' && isset($this->selectedFilters['price'])) {
            $this->parsePriceRanges($this->selectedFilters['price']);
        }

        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function getActiveFilters()
    {
        $activeFilters = [];

        foreach ($this->selectedFilters as $filterType => $selectedValues) {
            if (empty($selectedValues)) {
                continue;
            }

            foreach ($selectedValues as $value) {
                if ($filterType === 'price') {
                    // Handle price ranges specially
                    $label = match ($value) {
                        '0-100' => '€0 - €100',
                        '100-500' => '€100 - €500',
                        '500-1000' => '€500 - €1,000',
                        '1000-2000' => '€1,000 - €2,000',
                        '2000+' => '€2,000+',
                        default => $value
                    };
                    $activeFilters[] = [
                        'type' => $filterType,
                        'value' => $value,
                        'label' => $label,
                    ];
                } else {
                    // Handle all other filter types dynamically
                    if (isset($this->filterOptions[$filterType])) {
                        $option = collect($this->filterOptions[$filterType])->firstWhere('id', $value);
                        if ($option) {
                            $label = '';
                            
                            // Try to get translated name
                            if (is_object($option) && method_exists($option, 'getTranslation')) {
                                $label = $option->getTranslation('name', app()->getLocale());
                            } elseif (is_object($option) && property_exists($option, 'name')) {
                                if (is_array($option->name)) {
                                    $label = $option->name[app()->getLocale()] ?? $option->name['en'] ?? 'Unknown';
                                } else {
                                    $label = $option->name;
                                }
                            } elseif (is_array($option) && isset($option['name'])) {
                                if (is_array($option['name'])) {
                                    $label = $option['name'][app()->getLocale()] ?? $option['name']['en'] ?? 'Unknown';
                                } else {
                                    $label = $option['name'];
                                }
                            }

                            $activeFilters[] = [
                                'type' => $filterType,
                                'value' => $value,
                                'label' => $label ?: 'Unknown',
                            ];
                        }
                    }
                }
            }
        }

        return $activeFilters;
    }

    private function parsePriceRanges($values)
    {
        if (empty($values) || ! is_array($values)) {
            $this->priceRange = ['min' => null, 'max' => null];

            return;
        }

        // Find the overall min and max from selected ranges
        $allMins = [];
        $allMaxs = [];

        foreach ($values as $value) {
            switch ($value) {
                case '0-100':
                    $allMins[] = 0;
                    $allMaxs[] = 100;
                    break;
                case '100-500':
                    $allMins[] = 100;
                    $allMaxs[] = 500;
                    break;
                case '500-1000':
                    $allMins[] = 500;
                    $allMaxs[] = 1000;
                    break;
                case '1000-2000':
                    $allMins[] = 1000;
                    $allMaxs[] = 2000;
                    break;
                case '2000+':
                    $allMins[] = 2000;
                    // Don't add to allMaxs for open-ended range
                    break;
            }
        }

        // Set overall range based on selected options
        $this->priceRange = [
            'min' => ! empty($allMins) ? min($allMins) : null,
            'max' => ! empty($allMaxs) ? max($allMaxs) : null,
        ];

        // If 2000+ is selected, remove the max limit
        if (in_array('2000+', $values)) {
            $this->priceRange['max'] = null;
        }
    }

    private function setupUrlBasedFilters()
    {
        if (! $this->categorySlug) {
            return;
        }

        // Check if the slug corresponds to a brand, category or attribute (in that order)
        $brandRepository = new EloquentBrandRepository;
        $categoryRepository = new EloquentCategoryRepository;
        $attributeRepository = new EloquentAttributeRepository;
        $currentLocale = app()->getLocale();

        // First check if it's a brand slug
        $brands = $brandRepository->findActive();
        foreach ($brands as $brand) {
            // Handle translatable slugs
            if (is_array($brand->slug)) {
                // Check current locale first
                if (isset($brand->slug[$currentLocale]) && $brand->slug[$currentLocale] === $this->categorySlug) {
                    $this->setBrandData($brand, $currentLocale);
                    $this->urlBasedFilters['urlBasedBrands'] = [$brand->id];

                    return;
                }

                // Check all locales
                if (in_array($this->categorySlug, $brand->slug)) {
                    $this->setBrandData($brand, $currentLocale);
                    $this->urlBasedFilters['urlBasedBrands'] = [$brand->id];

                    return;
                }
            } else {
                // Fallback for non-translatable slugs
                if ($brand->slug === $this->categorySlug) {
                    $this->setBrandData($brand, $currentLocale);
                    $this->urlBasedFilters['urlBasedBrands'] = [$brand->id];

                    return;
                }
            }
        }

        // First check if it's a category
        $categories = $categoryRepository->findActive();
        foreach ($categories as $category) {
            // Check if slug matches in current locale
            if ($category->getTranslation('slug', $currentLocale) === $this->categorySlug) {
                $this->setCategoryData($category, $currentLocale);
                $this->urlBasedFilters['urlBasedCategories'] = [$category->id];
                return;
            }
            
            // Check if slug matches in any locale
            foreach (['en', 'es'] as $locale) {
                if ($category->getTranslation('slug', $locale) === $this->categorySlug) {
                    $this->setCategoryData($category, $currentLocale);
                    $this->urlBasedFilters['urlBasedCategories'] = [$category->id];
                    return;
                }
            }
        }

        // Then check if it's an attribute
        $attributes = $attributeRepository->findActiveRoots();
        foreach ($attributes as $attribute) {
            // Check if slug matches in current locale
            if ($attribute->getTranslation('slug', $currentLocale) === $this->categorySlug) {
                $this->setAttributeData($attribute, $currentLocale);
                $this->urlBasedFilters['urlBasedAttributes'] = [$attribute->id];
                return;
            }
            
            // Check if slug matches in any locale
            foreach (['en', 'es'] as $locale) {
                if ($attribute->getTranslation('slug', $locale) === $this->categorySlug) {
                    $this->setAttributeData($attribute, $currentLocale);
                    $this->urlBasedFilters['urlBasedAttributes'] = [$attribute->id];
                    return;
                }
            }
        }
    }

    private function setBrandData($brand = null, $locale = null)
    {
        if ($brand) {
            // Set brand data for dynamic content
            $this->brandData = $brand;
            $this->pageTitle = $this->getTranslationValue($brand->name, $locale) ?: $brand->name;
            $this->pageDescription = $this->getTranslationValue($brand->description_1, $locale) ?: '';
            $this->pageDescription2 = $this->getTranslationValue($brand->description_2, $locale) ?: '';
        } else {
            // Set default content
            $this->brandData = null;
            $this->pageTitle = __('shop.page_title');
            $this->pageDescription = __('shop.page_description');
            $this->pageDescription2 = __('shop.default_description_2');
        }
    }

    private function getTranslationValue($translatedField, $locale)
    {
        if (is_array($translatedField)) {
            return $translatedField[$locale] ?? $translatedField['en'] ?? '';
        }

        return $translatedField ?: '';
    }

    private function setCategoryData($category = null, $locale = null)
    {
        if ($category) {
            // Set category data for dynamic content
            $this->brandData = null; // Clear brand data
            $this->pageTitle = $category->getTranslation('name', $locale) ?: $category->name;
            $this->pageDescription = $category->getTranslation('description_1', $locale) ?: '';
            $this->pageDescription2 = $category->getTranslation('description_2', $locale) ?: '';
        } else {
            // Set default content
            $this->brandData = null;
            $this->pageTitle = __('shop.page_title');
            $this->pageDescription = __('shop.page_description');
            $this->pageDescription2 = __('shop.default_description_2');
        }
    }

    private function setAttributeData($attribute = null, $locale = null)
    {
        if ($attribute) {
            // Set attribute data for dynamic content
            $this->brandData = null; // Clear brand data
            $this->pageTitle = $attribute->getTranslation('name', $locale) ?: $attribute->name;
            $this->pageDescription = $attribute->getTranslation('description_1', $locale) ?: '';
            $this->pageDescription2 = $attribute->getTranslation('description_2', $locale) ?: '';
        } else {
            // Set default content
            $this->brandData = null;
            $this->pageTitle = __('shop.page_title');
            $this->pageDescription = __('shop.page_description');
            $this->pageDescription2 = __('shop.default_description_2');
        }
    }

    private function loadShopData()
    {
        $this->loading = true;

        // Create the use case with dependencies
        $getShopData = $this->createGetShopDataUseCase();

        // Prepare applied filters dynamically from selectedFilters
        $this->appliedFilters = [];
        
        foreach ($this->selectedFilters as $filterType => $filterValues) {
            if (!empty($filterValues)) {
                // Map filter types to expected keys for GetShopData
                switch ($filterType) {
                    case 'brand':
                        $this->appliedFilters['brands'] = $filterValues;
                        break;
                    case 'category':
                        $this->appliedFilters['categories'] = $filterValues;
                        break;
                    case 'attribute':
                        $this->appliedFilters['attributes'] = $filterValues;
                        break;
                    case 'quality':
                        $this->appliedFilters['qualities'] = $filterValues;
                        break;
                    case 'price':
                        $this->appliedFilters['priceRanges'] = $filterValues;
                        break;
                    default:
                        // For category-based filters (material, bag-type, etc.)
                        $this->appliedFilters[$filterType] = $filterValues;
                        break;
                }
            }
        }

        // Add URL-based filters (these should not pre-select UI filters)
        if (! empty($this->urlBasedFilters)) {
            $this->appliedFilters = array_merge($this->appliedFilters, $this->urlBasedFilters);
        }

        // Price ranges are already handled in the foreach loop above

        // Execute use case
        $result = $getShopData->execute($this->appliedFilters, $this->selectedSortBy, $this->categorySlug);

        $this->products = $result['products'];
        $this->filters = $result['filters'];
        $this->filterOptions = $result['filterOptions'];

        // Fallback: If no shop filters are configured, manually add brand filter
        if (empty($this->filterOptions['brand'])) {
            $brandRepository = new EloquentBrandRepository;
            $this->filterOptions['brand'] = $brandRepository->findActive();
        }

        $this->loading = false;
    }

    private function createGetShopDataUseCase(): GetShopData
    {
        // Create repository instances
        $productRepository = new EloquentProductRepository;
        $shopFilterRepository = new EloquentShopFilterRepository;
        $brandRepository = new EloquentBrandRepository;
        $categoryRepository = new EloquentCategoryRepository;
        $attributeRepository = new EloquentAttributeRepository;

        // Create quality repository instance
        $qualityRepository = new EloquentQualityRepository;

        return new GetShopData(
            $productRepository,
            $shopFilterRepository,
            $brandRepository,
            $categoryRepository,
            $attributeRepository,
            $qualityRepository
        );
    }

    public function render()
    {
        return view('livewire.shop')->extends('layouts.app');
    }

    private function parseUrlParameters()
    {
        if (!empty($this->urlFilters)) {
            $this->selectedFilters = json_decode($this->urlFilters, true) ?: [];
        }
    }

    private function syncUrlParameters()
    {
        $this->urlFilters = !empty($this->selectedFilters) ? json_encode($this->selectedFilters) : '';
    }

    // Helper methods to convert between IDs and slugs/codes
    private function convertBrandSlugsToIds(array $slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        $brandRepository = new EloquentBrandRepository;
        $brands = $brandRepository->findActive();
        $ids = [];

        foreach ($slugs as $slug) {
            foreach ($brands as $brand) {
                if ($brand->slug === $slug) {
                    $ids[] = $brand->id;
                    break;
                }
            }
        }

        return $ids;
    }

    private function convertBrandIdsToSlugs(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $brandRepository = new EloquentBrandRepository;
        $brands = $brandRepository->findActive();
        $slugs = [];

        foreach ($ids as $id) {
            foreach ($brands as $brand) {
                if ($brand->id === $id) {
                    $slugs[] = $brand->slug;
                    break;
                }
            }
        }

        return $slugs;
    }

    private function convertCategorySlugsToIds(array $slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        $categoryRepository = new EloquentCategoryRepository;
        $categories = $categoryRepository->findActive();
        $currentLocale = app()->getLocale();
        $ids = [];

        foreach ($slugs as $slug) {
            foreach ($categories as $category) {
                if (is_array($category->slug)) {
                    // Check current locale first
                    if (isset($category->slug[$currentLocale]) && $category->slug[$currentLocale] === $slug) {
                        $ids[] = $category->id;
                        break;
                    }

                    // Check other languages
                    if (in_array($slug, $category->slug)) {
                        $ids[] = $category->id;
                        break;
                    }
                } else {
                    // Fallback for non-translatable slugs
                    if ($category->slug === $slug) {
                        $ids[] = $category->id;
                        break;
                    }
                }
            }
        }

        return $ids;
    }

    private function convertCategoryIdsToSlugs(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $categoryRepository = new EloquentCategoryRepository;
        $categories = $categoryRepository->findActive();
        $currentLocale = app()->getLocale();
        $slugs = [];

        foreach ($ids as $id) {
            foreach ($categories as $category) {
                if ($category->id === $id) {
                    if (is_array($category->slug)) {
                        $slug = $category->slug[$currentLocale] ?? $category->slug['en'] ?? null;
                        if ($slug) {
                            $slugs[] = $slug;
                        }
                    } else {
                        // Fallback for non-translatable slugs
                        if ($category->slug) {
                            $slugs[] = $category->slug;
                        }
                    }
                    break;
                }
            }
        }

        return $slugs;
    }

    private function convertQualityCodesToIds(array $codes): array
    {
        if (empty($codes)) {
            return [];
        }

        $qualityRepository = new EloquentQualityRepository;
        $qualities = $qualityRepository->findAll();
        $ids = [];

        foreach ($codes as $code) {
            foreach ($qualities as $quality) {
                if ($quality->code === $code) {
                    $ids[] = $quality->id;
                    break;
                }
            }
        }

        return $ids;
    }

    private function convertQualityIdsToCodes(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $qualityRepository = new EloquentQualityRepository;
        $qualities = $qualityRepository->findAll();
        $codes = [];

        foreach ($ids as $id) {
            foreach ($qualities as $quality) {
                if ($quality->id === $id) {
                    $codes[] = $quality->code;
                    break;
                }
            }
        }

        return $codes;
    }

    private function convertAttributeSlugsToIds(array $slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        $attributeRepository = new EloquentAttributeRepository;
        $attributes = $attributeRepository->findActiveRoots();
        $currentLocale = app()->getLocale();
        $ids = [];

        foreach ($slugs as $slug) {
            foreach ($attributes as $attribute) {
                if (is_array($attribute->slug)) {
                    // Check current locale first
                    if (isset($attribute->slug[$currentLocale]) && $attribute->slug[$currentLocale] === $slug) {
                        $ids[] = $attribute->id;
                        break;
                    }

                    // Check other languages
                    if (in_array($slug, $attribute->slug)) {
                        $ids[] = $attribute->id;
                        break;
                    }
                } else {
                    // Fallback for non-translatable slugs
                    if (isset($attribute->slug) && $attribute->slug === $slug) {
                        $ids[] = $attribute->id;
                        break;
                    }
                }
            }
        }

        return $ids;
    }

    private function convertAttributeIdsToSlugs(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $attributeRepository = new EloquentAttributeRepository;
        $attributes = $attributeRepository->findActiveRoots();
        $currentLocale = app()->getLocale();
        $slugs = [];

        foreach ($ids as $id) {
            foreach ($attributes as $attribute) {
                if ($attribute->id === $id) {
                    if (is_array($attribute->slug)) {
                        $slug = $attribute->slug[$currentLocale] ?? $attribute->slug['en'] ?? null;
                        if ($slug) {
                            $slugs[] = $slug;
                        }
                    } else {
                        // Fallback for non-translatable slugs
                        if (isset($attribute->slug) && $attribute->slug) {
                            $slugs[] = $attribute->slug;
                        }
                    }
                    break;
                }
            }
        }

        return $slugs;
    }
}
