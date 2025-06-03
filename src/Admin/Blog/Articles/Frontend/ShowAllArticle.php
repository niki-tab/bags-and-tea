<?php

namespace Src\Admin\Blog\Articles\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Shared\Domain\Criteria\Order;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\Criteria;
use Src\Shared\Domain\Criteria\Filter;
use Src\Shared\Domain\Criteria\FilterField;
use Src\Shared\Domain\Criteria\FilterOperator;
use Src\Shared\Domain\Criteria\FilterValue;
use Src\Blog\Articles\Infrastructure\EloquentArticleRepository;
use Illuminate\Support\Collection;

class ShowAllArticle extends Component
{
    use WithPagination;

    public $lang;
    public $allArticles;
    public $articlesNotFoundText;
    public $selectedState = '';
    public $articleStates = [];
    public $stateFilter = '';

    public function mount($numberArticle = null)
    {   
        $this->lang = app()->getLocale();
        $this->loadArticles($numberArticle);
        $this->loadArticleStates();
    }

    public function updatedStateFilter()
    {
        $this->resetPage(); // Reset pagination when filter changes
        $this->loadArticles();
    }

    private function loadArticles($numberArticle = null)
    {
        $filters = [];
        
        if (!empty($this->stateFilter)) {
            $filters[] = new Filter(
                new FilterField('state'),
                new FilterOperator('='),
                new FilterValue($this->stateFilter)
            );
        }

        $orderBy = 'created_at';
        $order = 'desc';
        $offset = null;
        $limit = $numberArticle;
        
        $filters = new Filters($filters);
        $order = Order::fromValues($orderBy, $order);
        $criteria = new Criteria($filters, $order, $offset, $limit);

        $eloquentArticleRepository = new EloquentArticleRepository();
        $articles = $eloquentArticleRepository->searchByCriteria($criteria);
        
        $this->allArticles = collect($articles);

        if ($this->allArticles->isEmpty()) {
            $this->articlesNotFoundText = 'No articles found.';
        }
    }

    private function loadArticleStates()
    {
        $eloquentArticleRepository = new EloquentArticleRepository();
        $criteria = new Criteria(new Filters([]), Order::none(), null, null);
        $allArticles = collect($eloquentArticleRepository->searchByCriteria($criteria));
        
        $this->articleStates = $allArticles->pluck('state')->unique()->filter()->values()->toArray();
    }

    public function getArticleTitle($article)
    {
        if (method_exists($article, 'getTranslation')) {
            $title = $article->getTranslation('title', $this->lang);
            return $title ?: $article->title;
        }
        return $article->title ?? '';
    }

    public function getArticleSlug($article)
    {
        if (method_exists($article, 'getTranslation')) {
            $slug = $article->getTranslation('slug', $this->lang);
            return $slug ?: $article->slug;
        }
        return $article->slug ?? '';
    }

    public function getStateCount($state)
    {
        $eloquentArticleRepository = new EloquentArticleRepository();
        $filters = [
            new Filter(
                new FilterField('state'),
                new FilterOperator('='),
                new FilterValue($state)
            )
        ];
        $criteria = new Criteria(new Filters($filters), Order::none(), null, null);
        $articles = $eloquentArticleRepository->searchByCriteria($criteria);
        return count($articles);
    }

    public function getAllArticlesCount()
    {
        $eloquentArticleRepository = new EloquentArticleRepository();
        $criteria = new Criteria(new Filters([]), Order::none(), null, null);
        $articles = $eloquentArticleRepository->searchByCriteria($criteria);
        return count($articles);
    }

    public function render()
    {
        return view('livewire.admin.blog.articles.show-all');
    }
}