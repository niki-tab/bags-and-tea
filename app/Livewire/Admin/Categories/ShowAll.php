<?php

namespace App\Livewire\Admin\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;

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

    public function toggleActive($categoryId)
    {
        $category = CategoryEloquentModel::find($categoryId);
        if ($category) {
            $category->is_active = !$category->is_active;
            $category->save();
        }
    }

    public function render()
    {
        $categories = CategoryEloquentModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.categories.show-all', [
            'categories' => $categories
        ]);
    }
}