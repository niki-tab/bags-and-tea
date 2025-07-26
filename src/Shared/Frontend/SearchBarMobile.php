<?php

namespace Src\Shared\Frontend;

use Livewire\Component;
use Src\Products\Product\Application\ProductSearcher;

class SearchBarMobile extends Component
{
    public string $query = '';
    public array $results = [];
    public array $suggestions = [];
    public bool $showResults = false;
    public bool $isLoading = false;

    protected $rules = [
        'query' => 'string|max:255'
    ];

    public function mount()
    {
        $this->query = '';
        $this->results = [];
        $this->suggestions = [];
        $this->showResults = false;
    }

    public function updatedQuery()
    {
        if (strlen(trim($this->query)) < 2) {
            $this->resetSearch();
            return;
        }

        $this->isLoading = true;
        $this->performSearch();
        $this->isLoading = false;
    }

    public function performSearch()
    {
        $locale = app()->getLocale();
        $searchQuery = trim($this->query);

        if (strlen($searchQuery) < 2) {
            $this->resetSearch();
            return;
        }

        try {
            $productSearcher = app(ProductSearcher::class);
            
            $this->results = $productSearcher->search($searchQuery, $locale, 8);
            $this->suggestions = $productSearcher->getSuggestions($searchQuery, $locale, 5);
            
            // Always show results dropdown when we have a valid query (even if no results)
            $this->showResults = true;
        } catch (\Exception $e) {
            $this->resetSearch();
        }
    }

    public function selectSuggestion($suggestion)
    {
        $this->redirectToShop($suggestion);
    }

    public function resetSearch()
    {
        $this->results = [];
        $this->suggestions = [];
        $this->showResults = false;
        $this->isLoading = false;
    }

    public function hideResults()
    {
        $this->showResults = false;
    }

    public function goToProduct($productUrl)
    {
        return redirect($productUrl);
    }

    public function search()
    {
        if (strlen(trim($this->query)) >= 2) {
            $this->redirectToShop($this->query);
        }
    }

    private function redirectToShop($query)
    {
        $locale = app()->getLocale();
        $routeName = $locale === 'es' ? 'shop.show.es' : 'shop.show.en';
        
        return redirect()->route($routeName, [
            'locale' => $locale,
            'search' => trim($query)
        ]);
    }

    public function render()
    {
        return view('livewire.shared.search-bar-mobile');
    }
}