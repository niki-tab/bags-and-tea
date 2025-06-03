<?php

namespace Src\Admin\Product\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
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

    public function mount($numberProduct = null)
    {   
        $this->lang = app()->getLocale();

        $filters = [];
        $orderBy = 'name';
        $order = 'asc';
        $offset = null;
        $limit = $numberProduct;
        
        $filters = Filters::fromValues($filters);
        $order = Order::fromValues($orderBy, $order);
        $criteria = new Criteria($filters, $order, $offset, $limit);

        $eloquentProductRepository = new EloquentProductRepository();
        $this->allProducts = $eloquentProductRepository->searchByCriteria($criteria);

        if (!$this->allProducts || empty($this->allProducts)) {
            $this->productsNotFoundText = 'No products found.';
        }
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
        $brand = $product->getTranslation('brand', $this->lang);
        return $brand ?: $product->brand;
    }

    public function render()
    {
        return view('livewire.admin.products.show');
    }
}