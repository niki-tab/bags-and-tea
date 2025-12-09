<?php

namespace App\Livewire\Admin\BagSupplyHunting;

use Livewire\Component;
use Livewire\Attributes\On;
use Src\ThirdPartyServices\Vinted\Domain\Models\VintedListingEloquentModel;

class ShowAll extends Component
{
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 20;
    public $currentPage = 1;
    public $filterInteresting = '';
    public $filterVerified = '';
    public $filterNotificationSent = '';

    public function updatingSearch()
    {
        $this->currentPage = 1;
    }

    public function updatedFilterInteresting()
    {
        $this->currentPage = 1;
    }

    public function updatedFilterVerified()
    {
        $this->currentPage = 1;
    }

    public function updatedFilterNotificationSent()
    {
        $this->currentPage = 1;
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }

        $this->currentPage = 1;
    }

    #[On('pageChanged')]
    public function handlePageChanged($page)
    {
        $this->currentPage = $page;
    }

    public function render()
    {
        $query = VintedListingEloquentModel::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterInteresting !== '', function ($query) {
                $query->where('is_interesting', $this->filterInteresting === '1');
            })
            ->when($this->filterVerified !== '', function ($query) {
                $query->where('is_verified_product', $this->filterVerified === '1');
            })
            ->when($this->filterNotificationSent !== '', function ($query) {
                $query->where('notification_sent', $this->filterNotificationSent === '1');
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        $totalItems = $query->count();
        $listings = $query->skip(($this->currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();

        return view('livewire.admin.bag-supply-hunting.show-all', [
            'listings' => $listings,
            'totalItems' => $totalItems,
        ]);
    }
}
