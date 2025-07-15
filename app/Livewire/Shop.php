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

    // Individual URL parameters for clean URLs
    #[Url]
    public $brand = '';
    
    #[Url]
    public $category = '';
    
    #[Url]
    public $attribute = '';
    
    #[Url]
    public $quality = '';
    
    #[Url]
    public $price = '';
    
    #[Url(as: 'year-of-manufacture')]
    public $yearOfManufacture = '';
    
    #[Url]
    public $size = '';
    
    #[Url]
    public $color = '';
    
    #[Url]
    public $material = '';
    
    #[Url(as: 'bag-type')]
    public $bagType = '';

    #[Url(as: 'sort')]
    public $selectedSortBy = '';

    #[Url(as: 'page')]
    public $currentPage = 1;

    public $perPage = 8;

    public $totalProducts = 0;

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

        $this->loadShopData();

        // Parse URL parameters after shop data is loaded (so $this->filters is available)
        $this->parseUrlParameters();

        // Parse price ranges if they come from URL
        if (! empty($this->selectedPriceRange)) {
            $this->parsePriceRanges($this->selectedPriceRange);
        }

        // Reload shop data with parsed filters
        if (!empty($this->selectedFilters)) {
            $this->loadShopData();
        }
    }

    public function updatedSelectedFilters($value)
    {
        // Handle price range parsing if price filter was updated
        if (isset($this->selectedFilters['price'])) {
            $this->parsePriceRanges($this->selectedFilters['price']);
        }
        
        // Reset pagination when filters change
        $this->currentPage = 1;
        
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function updatedSelectedSortBy($value)
    {
        // Reset pagination when sorting changes
        $this->currentPage = 1;
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
        $this->currentPage = 1;
        $this->priceRange = ['min' => null, 'max' => null];
        
        // Clear all URL properties
        $this->brand = '';
        $this->category = '';
        $this->attribute = '';
        $this->quality = '';
        $this->price = '';
        $this->yearOfManufacture = '';
        $this->size = '';
        $this->color = '';
        $this->material = '';
        $this->bagType = '';
        
        $this->loadShopData();
    }

    public function removeFilter($type, $value)
    {
        // Convert slug/code to ID for internal storage (except price which stays as-is)
        $internalValue = $value; // Default fallback
        
        switch ($type) {
            case 'brand':
                $ids = $this->convertBrandSlugsToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'category':
                $ids = $this->convertCategorySlugsToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'attribute':
                $ids = $this->convertAttributeSlugsToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'quality':
                $ids = $this->convertQualityCodesToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'price':
                // Price ranges stay as-is
                $internalValue = $value;
                break;
            default:
                // For dynamic category/attribute filters, check the filter type
                if ($this->isAttributeFilterType($type)) {
                    $ids = $this->convertAttributeSlugsToIds([$value]);
                    $internalValue = !empty($ids) ? $ids[0] : $value;
                } elseif ($this->isCategoryFilterType($type)) {
                    $ids = $this->convertCategorySlugsToIds([$value]);
                    $internalValue = !empty($ids) ? $ids[0] : $value;
                } else {
                    $internalValue = $value; // Fallback for other filter types
                }
                break;
        }

        if (isset($this->selectedFilters[$type])) {
            $this->selectedFilters[$type] = array_values(array_diff($this->selectedFilters[$type], [$internalValue]));
            
            // Clean up empty filter arrays
            if (empty($this->selectedFilters[$type])) {
                unset($this->selectedFilters[$type]);
            }
            
            // Handle price range parsing
            if ($type === 'price' && isset($this->selectedFilters['price'])) {
                $this->parsePriceRanges($this->selectedFilters['price']);
            }
        }
        
        // Reset pagination when filters change
        $this->currentPage = 1;
        
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function toggleFilter($type, $value)
    {
        // Convert slug/code to ID for internal storage (except price which stays as-is)
        $internalValue = $value; // Default fallback
        
        switch ($type) {
            case 'brand':
                $ids = $this->convertBrandSlugsToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'category':
                $ids = $this->convertCategorySlugsToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'attribute':
                $ids = $this->convertAttributeSlugsToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'quality':
                $ids = $this->convertQualityCodesToIds([$value]);
                $internalValue = !empty($ids) ? $ids[0] : $value;
                break;
            case 'price':
                // Price ranges stay as-is
                $internalValue = $value;
                break;
            default:
                // For dynamic category/attribute filters, check the filter type
                if ($this->isAttributeFilterType($type)) {
                    $ids = $this->convertAttributeSlugsToIds([$value]);
                    $internalValue = !empty($ids) ? $ids[0] : $value;
                } elseif ($this->isCategoryFilterType($type)) {
                    $ids = $this->convertCategorySlugsToIds([$value]);
                    $internalValue = !empty($ids) ? $ids[0] : $value;
                } else {
                    $internalValue = $value; // Fallback for other filter types
                }
                break;
        }

        // Initialize filter array if it doesn't exist
        if (!isset($this->selectedFilters[$type])) {
            $this->selectedFilters[$type] = [];
        }

        // Check if the internal value is already selected
        if (in_array($internalValue, $this->selectedFilters[$type])) {
            // Remove it if it's already selected
            $this->selectedFilters[$type] = array_values(array_diff($this->selectedFilters[$type], [$internalValue]));
            
            // Clean up empty filter arrays
            if (empty($this->selectedFilters[$type])) {
                unset($this->selectedFilters[$type]);
            }
        } else {
            // Add it if it's not selected
            $this->selectedFilters[$type][] = $internalValue;
        }

        // Handle price range parsing
        if ($type === 'price' && isset($this->selectedFilters['price'])) {
            $this->parsePriceRanges($this->selectedFilters['price']);
        }

        // Reset pagination when filters change
        $this->currentPage = 1;

        $this->syncUrlParameters();
        $this->loadShopData();
    }

    // Pagination event listeners
    protected $listeners = ['pageChanged' => 'handlePageChanged'];

    public function handlePageChanged($page)
    {
        $this->currentPage = $page;
        
        // Small delay to make loading state visible (remove in production if not needed)
        usleep(500000); // 0.5 seconds
        
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
                        'value' => $value, // Price stays as-is (already user-friendly)
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

                            // Convert ID to slug/code for removal
                            $displayValue = $value; // Fallback
                            switch ($filterType) {
                                case 'brand':
                                    $slugs = $this->convertBrandIdsToSlugs([$value]);
                                    $displayValue = !empty($slugs) ? $slugs[0] : $value;
                                    break;
                                case 'category':
                                    $slugs = $this->convertCategoryIdsToSlugs([$value]);
                                    $displayValue = !empty($slugs) ? $slugs[0] : $value;
                                    break;
                                case 'attribute':
                                    $slugs = $this->convertAttributeIdsToSlugs([$value]);
                                    $displayValue = !empty($slugs) ? $slugs[0] : $value;
                                    break;
                                case 'quality':
                                    $codes = $this->convertQualityIdsToCodes([$value]);
                                    $displayValue = !empty($codes) ? $codes[0] : $value;
                                    break;
                                default:
                                    // For dynamic filters, check the filter type
                                    if ($this->isAttributeFilterType($filterType)) {
                                        $slugs = $this->convertAttributeIdsToSlugs([$value]);
                                        $displayValue = !empty($slugs) ? $slugs[0] : $value;
                                    } elseif ($this->isCategoryFilterType($filterType)) {
                                        $slugs = $this->convertCategoryIdsToSlugs([$value]);
                                        $displayValue = !empty($slugs) ? $slugs[0] : $value;
                                    }
                                    break;
                            }

                            $activeFilters[] = [
                                'type' => $filterType,
                                'value' => $displayValue, // Use slug/code for removal
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
            // Check current locale first, then fallback to English
            if ($brand->getTranslation('slug', $currentLocale) === $this->categorySlug || 
                $brand->getTranslation('slug', 'en') === $this->categorySlug) {
                $this->setBrandData($brand, $currentLocale);
                $this->urlBasedFilters['urlBasedBrands'] = [$brand->id];
                return;
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

        // Then check if it's an attribute (check all active attributes, not just roots)
        $attributes = $attributeRepository->findActive();
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
                        $this->appliedFilters['brands'] = array_merge($this->appliedFilters['brands'] ?? [], $filterValues);
                        break;
                    case 'category':
                        $this->appliedFilters['categories'] = array_merge($this->appliedFilters['categories'] ?? [], $filterValues);
                        break;
                    case 'attribute':
                        $this->appliedFilters['attributes'] = array_merge($this->appliedFilters['attributes'] ?? [], $filterValues);
                        break;
                    case 'quality':
                        $this->appliedFilters['qualities'] = array_merge($this->appliedFilters['qualities'] ?? [], $filterValues);
                        break;
                    case 'price':
                        $this->appliedFilters['priceRanges'] = array_merge($this->appliedFilters['priceRanges'] ?? [], $filterValues);
                        break;
                    default:
                        // Check if this is a dynamic attribute or category filter
                        if ($this->isAttributeFilterType($filterType)) {
                            // Map dynamic attribute filters to 'attributes' key
                            $this->appliedFilters['attributes'] = array_merge($this->appliedFilters['attributes'] ?? [], $filterValues);
                        } elseif ($this->isCategoryFilterType($filterType)) {
                            // Map dynamic category filters to 'categories' key  
                            $this->appliedFilters['categories'] = array_merge($this->appliedFilters['categories'] ?? [], $filterValues);
                        } else {
                            // For other filter types, keep as-is
                            $this->appliedFilters[$filterType] = $filterValues;
                        }
                        break;
                }
            }
        }

        // Add URL-based filters (these should not pre-select UI filters)
        if (! empty($this->urlBasedFilters)) {
            $this->appliedFilters = array_merge($this->appliedFilters, $this->urlBasedFilters);
        }

        // Price ranges are already handled in the foreach loop above

        // Calculate offset for pagination
        $offset = ($this->currentPage - 1) * $this->perPage;

        // Execute use case
        $result = $getShopData->execute($this->appliedFilters, $this->selectedSortBy, $this->categorySlug, $offset, $this->perPage);

        $this->products = $result['products'];
        $this->totalProducts = $result['totalCount'] ?? 0;
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
        $this->selectedFilters = [];
        
        // Map URL properties to selectedFilters with conversion
        $urlMappings = [
            'brand' => $this->brand,
            'category' => $this->category,
            'attribute' => $this->attribute,
            'quality' => $this->quality,
            'price' => $this->price,
            'year-of-manufacture' => $this->yearOfManufacture,
            'size' => $this->size,
            'color' => $this->color,
            'material' => $this->material,
            'bag-type' => $this->bagType,
        ];
        
        foreach ($urlMappings as $filterType => $urlValue) {
            if (empty($urlValue)) {
                continue;
            }
            
            // Convert comma-separated values to array
            $filterValues = explode(',', $urlValue);
            $filterValues = array_filter($filterValues); // Remove empty values
            
            if (empty($filterValues)) {
                continue;
            }
            
            // Convert slugs/codes back to IDs for internal use
            switch ($filterType) {
                case 'brand':
                    $this->selectedFilters[$filterType] = $this->convertBrandSlugsToIds($filterValues);
                    break;
                case 'category':
                    $this->selectedFilters[$filterType] = $this->convertCategorySlugsToIds($filterValues);
                    break;
                case 'attribute':
                    $this->selectedFilters[$filterType] = $this->convertAttributeSlugsToIds($filterValues);
                    break;
                case 'quality':
                    $this->selectedFilters[$filterType] = $this->convertQualityCodesToIds($filterValues);
                    break;
                case 'price':
                    // Price ranges stay as-is (they're already user-friendly)
                    $this->selectedFilters[$filterType] = $filterValues;
                    break;
                default:
                    // For dynamic category/attribute filters, check the filter type
                    if ($this->isAttributeFilterType($filterType)) {
                        $this->selectedFilters[$filterType] = $this->convertAttributeSlugsToIds($filterValues);
                    } elseif ($this->isCategoryFilterType($filterType)) {
                        $this->selectedFilters[$filterType] = $this->convertCategorySlugsToIds($filterValues);
                    } else {
                        // Fallback for other filter types
                        $this->selectedFilters[$filterType] = $filterValues;
                    }
                    break;
            }
        }
    }

    private function syncUrlParameters()
    {
        // Update individual URL properties based on selectedFilters
        $this->brand = '';
        $this->category = '';
        $this->attribute = '';
        $this->quality = '';
        $this->price = '';
        $this->yearOfManufacture = '';
        $this->size = '';
        $this->color = '';
        $this->material = '';
        $this->bagType = '';
        
        foreach ($this->selectedFilters as $filterType => $filterValues) {
            if (empty($filterValues)) {
                continue;
            }
            
            // Convert IDs to slugs for URL
            $urlValues = [];
            switch ($filterType) {
                case 'brand':
                    $urlValues = $this->convertBrandIdsToSlugs($filterValues);
                    $this->brand = implode(',', $urlValues);
                    break;
                case 'category':
                    $urlValues = $this->convertCategoryIdsToSlugs($filterValues);
                    $this->category = implode(',', $urlValues);
                    break;
                case 'attribute':
                    $urlValues = $this->convertAttributeIdsToSlugs($filterValues);
                    $this->attribute = implode(',', $urlValues);
                    break;
                case 'quality':
                    $urlValues = $this->convertQualityIdsToCodes($filterValues);
                    $this->quality = implode(',', $urlValues);
                    break;
                case 'price':
                    $this->price = implode(',', $filterValues);
                    break;
                case 'year-of-manufacture':
                    $urlValues = $this->convertAttributeIdsToSlugs($filterValues);
                    $this->yearOfManufacture = implode(',', $urlValues);
                    break;
                case 'size':
                    $urlValues = $this->convertAttributeIdsToSlugs($filterValues);
                    $this->size = implode(',', $urlValues);
                    break;
                case 'color':
                    $urlValues = $this->convertCategoryIdsToSlugs($filterValues);
                    $this->color = implode(',', $urlValues);
                    break;
                case 'material':
                    $urlValues = $this->convertCategoryIdsToSlugs($filterValues);
                    $this->material = implode(',', $urlValues);
                    break;
                case 'bag-type':
                    $urlValues = $this->convertCategoryIdsToSlugs($filterValues);
                    $this->bagType = implode(',', $urlValues);
                    break;
            }
        }
    }
    
    private function clearUrlParameters()
    {
        // Don't manually clear URLs - let the browser handle it naturally
    }

    // Helper method to check if a filter type is an attribute filter
    private function isAttributeFilterType($filterType): bool
    {
        // If filters aren't loaded yet, load them from repository
        if (empty($this->filters)) {
            $shopFilterRepository = new EloquentShopFilterRepository;
            $activeFilters = $shopFilterRepository->findActive();
        } else {
            $activeFilters = $this->filters;
        }
        
        // Check if this filter type matches any attribute filter_slug
        foreach ($activeFilters as $filter) {
            if ($filter->type === 'attribute' && !empty($filter->config)) {
                $config = is_array($filter->config) ? $filter->config : json_decode($filter->config, true);
                if (isset($config['filter_slug']) && $config['filter_slug'] === $filterType) {
                    return true;
                }
            }
        }
        return false;
    }

    // Helper method to check if a filter type is a category filter
    private function isCategoryFilterType($filterType): bool
    {
        // If filters aren't loaded yet, load them from repository
        if (empty($this->filters)) {
            $shopFilterRepository = new EloquentShopFilterRepository;
            $activeFilters = $shopFilterRepository->findActive();
        } else {
            $activeFilters = $this->filters;
        }
        
        // Check if this filter type matches any category filter_slug
        foreach ($activeFilters as $filter) {
            if ($filter->type === 'category' && !empty($filter->config)) {
                $config = is_array($filter->config) ? $filter->config : json_decode($filter->config, true);
                if (isset($config['filter_slug']) && $config['filter_slug'] === $filterType) {
                    return true;
                }
            }
        }
        return false;
    }

    // Helper methods to convert between IDs and slugs/codes
    private function convertBrandSlugsToIds(array $slugs): array
    {
        if (empty($slugs)) {
            return [];
        }

        $brandRepository = new EloquentBrandRepository;
        $brands = $brandRepository->findActive();
        $currentLocale = app()->getLocale();
        $ids = [];

        foreach ($slugs as $slug) {
            foreach ($brands as $brand) {
                // Check current locale first, then fallback to English
                if ($brand->getTranslation('slug', $currentLocale) === $slug || 
                    $brand->getTranslation('slug', 'en') === $slug) {
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
        $currentLocale = app()->getLocale();
        $slugs = [];

        foreach ($ids as $id) {
            foreach ($brands as $brand) {
                if ($brand->id === $id) {
                    // Use current locale, fallback to English
                    $slug = $brand->getTranslation('slug', $currentLocale) ?: $brand->getTranslation('slug', 'en');
                    if ($slug) {
                        $slugs[] = $slug;
                    }
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
                // Check current locale first, then fallback to English
                if ($category->getTranslation('slug', $currentLocale) === $slug || 
                    $category->getTranslation('slug', 'en') === $slug) {
                    $ids[] = $category->id;
                    break;
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
                    // Use current locale, fallback to English
                    $slug = $category->getTranslation('slug', $currentLocale) ?: $category->getTranslation('slug', 'en');
                    if ($slug) {
                        $slugs[] = $slug;
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
        $attributes = $attributeRepository->findAll(); // Look at ALL attributes, not just roots
        $currentLocale = app()->getLocale();
        $ids = [];

        foreach ($slugs as $slug) {
            foreach ($attributes as $attribute) {
                // Check current locale first, then fallback to English
                if ($attribute->getTranslation('slug', $currentLocale) === $slug || 
                    $attribute->getTranslation('slug', 'en') === $slug) {
                    $ids[] = $attribute->id;
                    break;
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
        $attributes = $attributeRepository->findAll(); // Look at ALL attributes, not just roots
        $currentLocale = app()->getLocale();
        $slugs = [];

        foreach ($ids as $id) {
            foreach ($attributes as $attribute) {
                if ($attribute->id === $id) {
                    // Use current locale, fallback to English
                    $slug = $attribute->getTranslation('slug', $currentLocale) ?: $attribute->getTranslation('slug', 'en');
                    if ($slug) {
                        $slugs[] = $slug;
                    }
                    break;
                }
            }
        }

        return $slugs;
    }
}
