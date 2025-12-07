<?php

namespace App\Livewire\Admin\BagSearchQueries;

use Livewire\Component;
use Illuminate\Support\Str;
use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;

class CreateForm extends Component
{
    public $name = '';
    public $brand = '';
    public $ideal_price = '';
    public $min_price = '50';
    public $max_price = '';
    public $vinted_search_url = '';
    public $max_pages = 3;
    public $page_param = 'page';
    public $platform = 'vinted';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'brand' => 'nullable|string|max:255',
        'ideal_price' => 'required|numeric|min:0',
        'min_price' => 'nullable|numeric|min:0',
        'max_price' => 'nullable|numeric|min:0',
        'vinted_search_url' => 'required|url|max:2000',
        'max_pages' => 'required|integer|min:1|max:50',
        'page_param' => 'required|string|max:50',
        'platform' => 'required|in:vinted',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        BagSearchQueryEloquentModel::create([
            'id' => Str::uuid()->toString(),
            'name' => $this->name,
            'brand' => $this->brand ?: null,
            'ideal_price' => $this->ideal_price,
            'min_price' => $this->min_price ?: null,
            'max_price' => $this->max_price ?: null,
            'vinted_search_url' => $this->vinted_search_url,
            'max_pages' => $this->max_pages,
            'page_param' => $this->page_param,
            'platform' => $this->platform,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Bag search query created successfully!');

        return redirect()->route('admin.bag-search-queries');
    }

    public function render()
    {
        return view('livewire.admin.bag-search-queries.create-form');
    }
}
