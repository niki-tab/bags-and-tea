<?php

namespace App\Livewire\Admin\BagSearchQueries;

use Livewire\Component;
use Livewire\WithPagination;
use Src\ThirdPartyServices\Vinted\Domain\Models\BagSearchQueryEloquentModel;

class ShowAll extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'name';
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

        $this->resetPage();
        $this->currentPage = 1;
    }

    public function toggleActive($id)
    {
        $query = BagSearchQueryEloquentModel::find($id);
        if ($query) {
            $query->is_active = !$query->is_active;
            $query->save();
        }
    }

    public function delete($id)
    {
        $query = BagSearchQueryEloquentModel::find($id);
        if ($query) {
            $query->delete();
            session()->flash('success', 'Bag search query deleted successfully!');
        }
    }

    public function render()
    {
        $this->currentPage = $this->getPage();

        $queries = BagSearchQueryEloquentModel::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('brand', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.bag-search-queries.show-all', [
            'queries' => $queries
        ]);
    }
}
