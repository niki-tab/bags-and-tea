<?php

namespace Src\Products\Product\Frontend;

use Livewire\Component;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class ProductCard extends Component
{
    public ProductEloquentModel $product;

    public function mount(ProductEloquentModel $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.products.product-card');
    }
}