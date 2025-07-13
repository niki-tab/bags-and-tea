<?php

namespace Src\Shared\Frontend;

use Livewire\Component;

class Pagination extends Component
{
    // Required props
    public int $currentPage = 1;
    public int $totalItems = 0;
    public int $perPage = 12;
    
    // Optional props
    public bool $showPerPageSelector = false;
    public string $paginationClass = '';
    public int $maxVisible = 5; // Maximum visible page numbers
    
    // Internal computed properties
    public function getTotalPagesProperty()
    {
        return (int) ceil($this->totalItems / $this->perPage);
    }
    
    public function getHasMorePagesProperty()
    {
        return $this->totalPages > 1;
    }
    
    public function getHasPreviousPageProperty()
    {
        return $this->currentPage > 1;
    }
    
    public function getHasNextPageProperty()
    {
        return $this->currentPage < $this->totalPages;
    }
    
    public function getPreviousPageProperty()
    {
        return max(1, $this->currentPage - 1);
    }
    
    public function getNextPageProperty()
    {
        return min($this->totalPages, $this->currentPage + 1);
    }
    
    public function getVisiblePagesProperty()
    {
        $totalPages = $this->totalPages;
        $current = $this->currentPage;
        $maxVisible = $this->maxVisible;
        
        if ($totalPages <= $maxVisible) {
            return range(1, $totalPages);
        }
        
        $start = max(1, $current - floor($maxVisible / 2));
        $end = min($totalPages, $start + $maxVisible - 1);
        
        // Adjust start if we're near the end
        if ($end - $start + 1 < $maxVisible) {
            $start = max(1, $end - $maxVisible + 1);
        }
        
        return range($start, $end);
    }
    
    public function getShowFirstProperty()
    {
        return !in_array(1, $this->visiblePages) && $this->totalPages > $this->maxVisible;
    }
    
    public function getShowLastProperty()
    {
        return !in_array($this->totalPages, $this->visiblePages) && $this->totalPages > $this->maxVisible;
    }
    
    public function mount()
    {
        // Ensure current page is within valid range
        $this->currentPage = max(1, min($this->currentPage, $this->totalPages ?: 1));
    }
    
    public function goToPage($page)
    {
        $page = max(1, min($page, $this->totalPages));
        
        if ($page !== $this->currentPage) {
            $this->currentPage = $page;
            $this->emitPageChanged();
        }
    }
    
    public function previousPage()
    {
        if ($this->hasPreviousPage) {
            $this->goToPage($this->previousPage);
        }
    }
    
    public function nextPage()
    {
        if ($this->hasNextPage) {
            $this->goToPage($this->nextPage);
        }
    }
    
    public function firstPage()
    {
        $this->goToPage(1);
    }
    
    public function lastPage()
    {
        $this->goToPage($this->totalPages);
    }
    
    private function emitPageChanged()
    {
        // Dispatch event for parent components to listen to
        $this->dispatch('pageChanged', $this->currentPage);
        
        // Update URL with current page (handled automatically by Livewire 3)
    }
    
    public function render()
    {
        return view('livewire.shared.pagination');
    }
}