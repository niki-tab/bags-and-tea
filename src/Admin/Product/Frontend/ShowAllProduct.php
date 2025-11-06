<?php

namespace Src\Admin\Product\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Src\Shared\Domain\Criteria\Order;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Infrastructure\EloquentProductRepository;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class ShowAllProduct extends Component
{
    public $lang;
    public $allProducts = [];
    public $allProductsForStats = [];
    public $productsNotFoundText;

    // Sorting properties
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Pagination
    public $perPage = 30;
    public $currentPage = 1;
    public $totalProducts = 0;

    // Pagination event listener
    protected $listeners = ['pageChanged' => 'handlePageChanged'];

    public function mount($numberProduct = null)
    {
        $this->lang = app()->getLocale();
        $this->loadProducts();
    }

    public function handlePageChanged($page)
    {
        $this->currentPage = $page;
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $filters = [];
        $orderBy = $this->sortField;
        $order = $this->sortDirection;

        // Calculate offset for pagination
        $offset = ($this->currentPage - 1) * $this->perPage;
        $limit = $this->perPage;

        $filters = Filters::fromValues($filters);
        $order = Order::fromValues($orderBy, $order);
        $criteria = new Criteria($filters, $order, $offset, $limit);

        $eloquentProductRepository = new EloquentProductRepository();
        $user = Auth::user();

        // Get all products for total count (without pagination)
        $allCriteria = new Criteria($filters, $order, null, null);

        // If user is vendor, show only their products
        if ($user && $user->hasRole('vendor')) {
            $this->allProducts = $eloquentProductRepository->searchByCriteriaForUser($user->id, $criteria);
            $this->allProductsForStats = $eloquentProductRepository->searchByCriteriaForUser($user->id, $allCriteria);
            $this->totalProducts = count($this->allProductsForStats);
        } else {
            // If user is admin, show all products
            $this->allProducts = $eloquentProductRepository->searchByCriteria($criteria);
            $this->allProductsForStats = $eloquentProductRepository->searchByCriteria($allCriteria);
            $this->totalProducts = count($this->allProductsForStats);
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

        // Reset pagination when sorting changes
        $this->currentPage = 1;
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
    
    public function getProductSlug($product)
    {
        $slug = $product->getTranslation('slug', $this->lang);
        return $slug ?: $product->slug;
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

    public function deleteProduct($productId)
    {
        try {
            $eloquentProductRepository = new EloquentProductRepository();
            $product = $eloquentProductRepository->search($productId);
            
            if (!$product) {
                session()->flash('error', 'Product not found.');
                return;
            }

            // Check permissions - only admin or product owner can delete
            $user = Auth::user();
            if (!$user->hasRole('admin') && $product->vendor_id !== $user->vendor?->id) {
                session()->flash('error', 'You do not have permission to delete this product.');
                return;
            }

            // Delete related records first
            // Delete product media
            $product->media()->delete();
            
            // Detach categories (removes pivot table entries)
            $product->categories()->detach();
            
            // Detach attributes (removes pivot table entries)  
            $product->attributes()->detach();
            
            // Delete the product itself
            $product->delete();

            session()->flash('success', 'Product and all related data deleted successfully.');

            // Reload products
            $this->loadProducts();

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the product.');
        }
    }

    public function duplicateProduct($productId)
    {
        try {
            $eloquentProductRepository = new EloquentProductRepository();
            $originalProduct = $eloquentProductRepository->search($productId);
            
            if (!$originalProduct) {
                session()->flash('error', 'Product not found.');
                return;
            }

            // Check permissions - only admin or product owner can duplicate
            $user = Auth::user();
            if (!$user->hasRole('admin') && $originalProduct->vendor_id !== $user->vendor?->id) {
                session()->flash('error', 'You do not have permission to duplicate this product.');
                return;
            }

            // Create a copy of the product
            $newProductData = $originalProduct->toArray();
            
            // Generate new unique identifiers
            $newProductData['id'] = (string) Str::uuid();
            $newProductData['slug'] = $originalProduct->slug . '-copy-' . time();
            $newProductData['name'] = $originalProduct->name . ' (Copy)';
            $newProductData['featured'] = false; // Don't duplicate featured status
            $newProductData['featured_position'] = null;
            
            // Remove timestamps to let Laravel handle them
            unset($newProductData['created_at'], $newProductData['updated_at']);
            
            // Create the new product
            $newProduct = ProductEloquentModel::create($newProductData);
            
            // Copy translations using a more robust approach
            $translatableFields = $originalProduct->getTranslatableAttributes();
            $availableLocales = config('app.available_locales', ['en', 'es']); // Fallback to common locales
            
            foreach ($availableLocales as $locale) {
                foreach ($translatableFields as $field) {
                    $originalValue = $originalProduct->getTranslation($field, $locale, false); // Don't fallback
                    
                    if ($originalValue) {
                        if ($field === 'name') {
                            $newProduct->setTranslation($field, $locale, $originalValue . ' (Copy)');
                        } elseif ($field === 'slug') {
                            $newProduct->setTranslation($field, $locale, $originalValue . '-copy-' . time());
                        } else {
                            $newProduct->setTranslation($field, $locale, $originalValue);
                        }
                    }
                }
            }
            $newProduct->save();
            
            // Copy categories
            $categoryIds = $originalProduct->categories()->pluck('categories.id')->toArray();
            $newProduct->categories()->attach($categoryIds);
            
            // Copy attributes
            $attributeIds = $originalProduct->attributes()->pluck('attributes.id')->toArray();
            $newProduct->attributes()->attach($attributeIds);
            
            // Copy media
            foreach ($originalProduct->media as $media) {
                $newMediaData = $media->toArray();
                $newMediaData['id'] = (string) Str::uuid();
                $newMediaData['product_id'] = $newProduct->id;
                unset($newMediaData['created_at'], $newMediaData['updated_at']);
                
                \Src\Products\Product\Infrastructure\Eloquent\ProductMediaModel::create($newMediaData);
            }
            
            session()->flash('success', 'Product duplicated successfully. You can now edit the copy.');

            // Reload products
            $this->loadProducts();

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while duplicating the product.');
        }
    }

    public function render()
    {
        return view('livewire.admin.products.show');
    }
}