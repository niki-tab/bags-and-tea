<?php

namespace App\Livewire\Admin\Attributes;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;

class ShowAll extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'display_order';
    public $sortDirection = 'asc';
    public $perPage = 15;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleActive($attributeId)
    {
        $attribute = AttributeEloquentModel::find($attributeId);
        if ($attribute) {
            $attribute->is_active = !$attribute->is_active;
            $attribute->save();
        }
    }

    public function render()
    {
        $attributes = AttributeEloquentModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.attributes.show-all', [
            'attributes' => $attributes
        ]);
    }
}