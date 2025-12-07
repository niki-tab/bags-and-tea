<?php

namespace App\Livewire\Admin\BagSearchQueries;

use Livewire\Component;
use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;

class EditForm extends Component
{
    public $queryId;
    public $bagSearchQuery;

    public $name = '';
    public $brand = '';
    public $ideal_price = '';
    public $min_price = '';
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

    public function mount($queryId)
    {
        $this->queryId = $queryId;
        $this->loadQuery();
    }

    public function loadQuery()
    {
        $this->bagSearchQuery = BagSearchQueryEloquentModel::findOrFail($this->queryId);

        $this->name = $this->bagSearchQuery->name;
        $this->brand = $this->bagSearchQuery->brand ?? '';
        $this->ideal_price = $this->bagSearchQuery->ideal_price;
        $this->min_price = $this->bagSearchQuery->min_price ?? '';
        $this->max_price = $this->bagSearchQuery->max_price ?? '';
        $this->vinted_search_url = $this->bagSearchQuery->vinted_search_url;
        $this->max_pages = $this->bagSearchQuery->max_pages ?? 3;
        $this->page_param = $this->bagSearchQuery->page_param ?? 'page';
        $this->platform = $this->bagSearchQuery->platform ?? 'vinted';
        $this->is_active = $this->bagSearchQuery->is_active;
    }

    public function save()
    {
        $this->validate();

        $this->bagSearchQuery->update([
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

        session()->flash('success', 'Bag search query updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.bag-search-queries.edit-form');
    }
}
