<?php

declare(strict_types=1);

namespace Src\Products\Shop\Application;

use Src\Products\Product\Domain\ProductRepository;
use Src\Products\Shop\Domain\ShopFilterRepository;
use Src\Products\Brands\Domain\BrandRepository;
use Src\Categories\Domain\CategoryRepository;
use Src\Attributes\Domain\AttributeRepository;
use Src\Products\Quality\Domain\QualityRepository;
use Src\Shared\Domain\Criteria\Criteria;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\Filter;
use Src\Shared\Domain\Criteria\FilterField;
use Src\Shared\Domain\Criteria\FilterOperator;
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

    public function execute(array $appliedFilters = [], string $sortBy = ''): array
    {
        // Get active shop filters configuration
        $activeFilters = $this->shopFilterRepository->findActive();

        // Get filter options for each active filter
        $filterOptions = [];
        foreach ($activeFilters as $filter) {
            $filterOptions[$filter->type] = $this->getFilterOptions($filter);
        }

        // Get filtered products
        $products = $this->getFilteredProducts($appliedFilters, $sortBy);

        return [
            'filters' => $activeFilters,
            'filterOptions' => $filterOptions,
            'products' => $products,
            'appliedFilters' => $appliedFilters
        ];
    }

    private function getFilterOptions($filter): array
    {
        switch ($filter->type) {
            case 'brand':
                return $this->brandRepository->findActive();
            
            case 'category':
                return $this->categoryRepository->findActiveRoots();
            
            case 'attribute':
                return $this->attributeRepository->findActiveRoots();
            
            case 'quality':
                // Assuming QualityRepository has similar methods
                return $this->qualityRepository->findAll();
            
            case 'price':
                return $this->getPriceRanges();
            
            default:
                return [];
        }
    }

    private function getFilteredProducts(array $appliedFilters, string $sortBy = ''): array
    {
        $filters = [];
        
        foreach ($appliedFilters as $filterType => $filterValues) {
            if (empty($filterValues)) {
                continue;
            }

            switch ($filterType) {
                case 'brands':
                    if (!empty($filterValues)) {
                        // Convert array to string for FilterValue - join with commas for 'in' operator
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('brand_id'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'categories':
                    if (!empty($filterValues)) {
                        // For categories, we need to join with the pivot table
                        // This might require a custom implementation in the repository
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('categories'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'attributes':
                    if (!empty($filterValues)) {
                        // Similar to categories, for pivot table relationships
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('attributes'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'qualities':
                    if (!empty($filterValues)) {
                        $filterValueString = is_array($filterValues) ? implode(',', $filterValues) : $filterValues;
                        $filters[] = new Filter(
                            new FilterField('quality_id'),
                            new FilterOperator('in'),
                            new FilterValue($filterValueString)
                        );
                    }
                    break;

                case 'priceRanges':
                    if (!empty($filterValues) && is_array($filterValues)) {
                        // Create a single filter with comma-separated values for the repository to handle
                        $filters[] = new Filter(
                            new FilterField('price_ranges'),
                            new FilterOperator('='),
                            new FilterValue(implode(',', $filterValues))
                        );
                    }
                    break;
            }
        }

        // Create sorting order
        $order = $this->createOrder($sortBy);

        // If no filters are applied, get all products
        if (empty($filters)) {
            $criteria = new Criteria(
                new Filters([]),
                $order,
                null,
                50
            );
        } else {
            $criteria = new Criteria(
                new Filters($filters),
                $order,
                null, // offset
                50    // limit
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
}