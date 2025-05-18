<?php

namespace App\Livewire;

use Livewire\Component;

use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class Shop extends Component
{
    public $products;

    public function mount()
    {
        $this->products = ProductEloquentModel::all();
    }

    public function render()
    {
        return view('livewire.shop')->extends('layouts.app');
    }
}
