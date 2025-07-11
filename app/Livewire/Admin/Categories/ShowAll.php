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
    public $currentPage = 1;

    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = ['pageChanged' => 'handlePageChanged'];

    public function updatingSearch()
    {
        $this->resetPage();
        $this->currentPage = 1;
    }

    public function updatedSortBy()
    {
        $this->resetPage();
        $this->currentPage = 1;
    }

    public function updatedSortDirection()
    {
        $this->resetPage();
        $this->currentPage = 1;
    }
    
    public function handlePageChanged($page)
    {
        $this->currentPage = $page;
        $this->setPage($page);
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        
        // Reset to first page when sorting
        $this->resetPage();
        $this->currentPage = 1;
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
        // Sync current page with Livewire pagination
        $this->currentPage = $this->getPage();
        
        $categories = CategoryEloquentModel::query()
            ->with('parent')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortBy === 'name', function ($query) {
                $query->orderBy('name->en', $this->sortDirection);
            })
            ->when($this->sortBy === 'slug', function ($query) {
                $query->orderBy('slug->en', $this->sortDirection);
            })
            ->when($this->sortBy === 'parent_id', function ($query) {
                $query->leftJoin('categories as parent_categories', 'categories.parent_id', '=', 'parent_categories.id')
                      ->orderBy('parent_categories.name->en', $this->sortDirection)
                      ->select('categories.*');
            })
            ->when($this->sortBy === 'is_active', function ($query) {
                $query->orderBy('is_active', $this->sortDirection);
            })
            ->when($this->sortBy === 'display_order', function ($query) {
                $query->orderBy('display_order', $this->sortDirection);
            })
            ->when($this->sortBy === 'created_at', function ($query) {
                $query->orderBy('created_at', $this->sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.categories.show-all', [
            'categories' => $categories
        ]);
    }
}