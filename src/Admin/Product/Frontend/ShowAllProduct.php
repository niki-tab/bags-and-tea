<?php

namespace Src\Admin\Product\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Domain\Criteria\Order;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Infrastructure\EloquentProductRepository;

class ShowAllProduct extends Component
{
    use WithPagination;

    public $lang;
    public $allProducts;
    public $productsNotFoundText;
    
    // Sorting properties
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function mount($numberProduct = null)
    {   
        $this->lang = app()->getLocale();
        $this->loadProducts($numberProduct);
    }

    public function loadProducts($numberProduct = null)
    {
        $filters = [];
        $orderBy = $this->sortField;
        $order = $this->sortDirection;
        $offset = null;
        $limit = $numberProduct;
        
        $filters = Filters::fromValues($filters);
        $order = Order::fromValues($orderBy, $order);
        $criteria = new Criteria($filters, $order, $offset, $limit);

        $eloquentProductRepository = new EloquentProductRepository();
        $user = Auth::user();

        // If user is vendor, show only their products
        if ($user && $user->hasRole('vendor')) {
            $this->allProducts = $eloquentProductRepository->searchByCriteriaForUser($user->id, $criteria);
        } else {
            // If user is admin, show all products
            $this->allProducts = $eloquentProductRepository->searchByCriteria($criteria);
        }

        if (!$this->allProducts || empty($this->allProducts)) {
            $this->productsNotFoundText = 'No products found.';
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadProducts();
    }

    public function formatPrice($price)
    {
        return number_format($price, 2, ',', '.') . ' €';
    }

    public function getProductName($product)
    {
        $name = $product->getTranslation('name', $this->lang);
        return $name ?: $product->name;
    }

    public function getProductBrand($product)
    {
        // Since 'brand' is not a translatable attribute on the product, we get the brand through the relationship
        $brand = $product->brand;
        if ($brand) {
            // The brand model has translatable attributes, so we use getTranslation for the brand name
            return $brand->getTranslation('name', $this->lang) ?: $brand->name;
        }
        return 'No brand';
    }

    public function getProductVendor($product)
    {
        $vendor = $product->vendor;
        if ($vendor && $vendor->user) {
            return $vendor->business_name ?: $vendor->user->name;
        }
        return 'No vendor assigned';
    }

    public function isCurrentUserAdmin()
    {
        $user = Auth::user();
        return $user && $user->hasRole('admin');
    }

    public function render()
    {
        return view('livewire.admin.products.show');
    }
}