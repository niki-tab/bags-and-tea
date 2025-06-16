<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Url;
use Src\Products\Shop\Application\GetShopData;
use Src\Products\Product\Infrastructure\EloquentProductRepository;
use Src\Products\Shop\Infrastructure\EloquentShopFilterRepository;
use Src\Products\Brands\Infrastructure\EloquentBrandRepository;
use Src\Categories\Infrastructure\EloquentCategoryRepository;
use Src\Attributes\Infrastructure\EloquentAttributeRepository;
use Src\Products\Quality\Infrastructure\EloquentQualityRepository;

class Shop extends Component
{
    public $products = [];
    public $filters = [];
    public $filterOptions = [];
    public $appliedFilters = [];
    
    // Filter state properties - internal arrays
    public $selectedBrand = [];
    public $selectedCategory = [];
    public $selectedAttribute = [];
    public $selectedQuality = [];
    public $selectedPriceRange = [];
    
    // URL-friendly string representations
    #[Url(as: 'brands')]
    public $urlBrands = '';
    
    #[Url(as: 'categories')]
    public $urlCategories = '';
    
    #[Url(as: 'attributes')]
    public $urlAttributes = '';
    
    #[Url(as: 'qualities')]
    public $urlQualities = '';
    
    #[Url(as: 'price')]
    public $urlPrice = '';
    
    #[Url(as: 'sort')]
    public $selectedSortBy = '';
    
    public $priceRange = ['min' => null, 'max' => null];
    
    // UI state
    public $loading = false;

    public function mount()
    {
        // Parse URL parameters to internal arrays
        $this->parseUrlParameters();
        
        // Parse price ranges if they come from URL
        if (!empty($this->selectedPriceRange)) {
            $this->parsePriceRanges($this->selectedPriceRange);
        }
        
        $this->loadShopData();
    }

    public function updatedSelectedBrand($value)
    {
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function updatedSelectedCategory($value)
    {
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function updatedSelectedAttribute($value)
    {
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function updatedSelectedQuality($value)
    {
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    public function updatedSelectedPriceRange($value)
    {
        // Ensure $value is treated as the full array, not individual values
        $this->parsePriceRanges($this->selectedPriceRange);
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
        $this->selectedBrand = [];
        $this->selectedCategory = [];
        $this->selectedAttribute = [];
        $this->selectedQuality = [];
        $this->selectedPriceRange = [];
        $this->selectedSortBy = '';
        $this->priceRange = ['min' => null, 'max' => null];
        $this->syncUrlParameters();
        $this->loadShopData();
    }

    private function parsePriceRanges($values)
    {
        if (empty($values) || !is_array($values)) {
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
            'min' => !empty($allMins) ? min($allMins) : null,
            'max' => !empty($allMaxs) ? max($allMaxs) : null
        ];

        // If 2000+ is selected, remove the max limit
        if (in_array('2000+', $values)) {
            $this->priceRange['max'] = null;
        }
    }

    private function loadShopData()
    {
        $this->loading = true;
        
        // Create the use case with dependencies
        $getShopData = $this->createGetShopDataUseCase();
        
        // Prepare applied filters
        $this->appliedFilters = [
            'brands' => $this->selectedBrand ?: [],
            'categories' => $this->selectedCategory ?: [],
            'attributes' => $this->selectedAttribute ?: [],
            'qualities' => $this->selectedQuality ?: [],
        ];
        
        // Add price ranges if any are selected
        if (!empty($this->selectedPriceRange)) {
            $this->appliedFilters['priceRanges'] = $this->selectedPriceRange;
        }
        
        
        // Execute use case
        $result = $getShopData->execute($this->appliedFilters, $this->selectedSortBy);
        
        $this->products = $result['products'];
        $this->filters = $result['filters'];
        $this->filterOptions = $result['filterOptions'];
        
        // Fallback: If no shop filters are configured, manually add brand filter
        if (empty($this->filterOptions['brand'])) {
            $brandRepository = new EloquentBrandRepository();
            $this->filterOptions['brand'] = $brandRepository->findActive();
        }
        
        
        $this->loading = false;
    }

    private function createGetShopDataUseCase(): GetShopData
    {
        // Create repository instances
        $productRepository = new EloquentProductRepository();
        $shopFilterRepository = new EloquentShopFilterRepository();
        $brandRepository = new EloquentBrandRepository();
        $categoryRepository = new EloquentCategoryRepository();
        $attributeRepository = new EloquentAttributeRepository();
        
        // Create quality repository instance
        $qualityRepository = new EloquentQualityRepository();
        
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
        // Convert semicolon-separated strings to arrays
        $this->selectedBrand = $this->urlBrands ? explode(';', $this->urlBrands) : [];
        $this->selectedCategory = $this->urlCategories ? explode(';', $this->urlCategories) : [];
        $this->selectedAttribute = $this->urlAttributes ? explode(';', $this->urlAttributes) : [];
        $this->selectedQuality = $this->urlQualities ? explode(';', $this->urlQualities) : [];
        $this->selectedPriceRange = $this->urlPrice ? explode(';', $this->urlPrice) : [];
    }

    private function syncUrlParameters()
    {
        // Convert arrays to semicolon-separated strings for clean URLs
        $this->urlBrands = !empty($this->selectedBrand) ? implode(';', $this->selectedBrand) : '';
        $this->urlCategories = !empty($this->selectedCategory) ? implode(';', $this->selectedCategory) : '';
        $this->urlAttributes = !empty($this->selectedAttribute) ? implode(';', $this->selectedAttribute) : '';
        $this->urlQualities = !empty($this->selectedQuality) ? implode(';', $this->selectedQuality) : '';
        $this->urlPrice = !empty($this->selectedPriceRange) ? implode(';', $this->selectedPriceRange) : '';
    }
}
