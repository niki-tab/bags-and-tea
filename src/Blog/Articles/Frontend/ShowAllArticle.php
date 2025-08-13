<?php

namespace Src\Blog\Articles\Frontend;

use Livewire\Component;
use Src\Blog\Articles\Model\ArticleModel;
use Src\Blog\Categories\Infrastructure\Eloquent\BlogCategoryEloquentModel;

class ShowAllArticle extends Component
{
    public $lang;
    public $selectedCategory;

    public $allArticles;
    public array $articlesNotFoundTextTranslations;

    public function mount($numberArticles = null)
    {   
        
        $this->lang = app()->getLocale();
        
        // Check for blogCategory query parameter
        $this->selectedCategory = request()->query('blogCategory');

        // Build the base query
        $query = ArticleModel::with('categories')
            ->where(function($q) {
                $q->where('state', 'live')->orWhere('state', 'published');
            })
            ->orderBy('created_at', 'desc');
        
        // Apply category filter if present
        if ($this->selectedCategory) {
            $category = BlogCategoryEloquentModel::where('slug->en', $this->selectedCategory)
                ->orWhere('slug->es', $this->selectedCategory)
                ->first();
                
            if ($category) {
                $query->whereHas('categories', function($q) use ($category) {
                    $q->where('blog_categories.id', $category->id);
                });
            }
        }

        $articleModel = $query->get()
            ->map->getTranslatedAttributes($this->lang)
            ->filter(function ($article) {
                return !empty($article['slug']);
            });

        if($numberArticles){
            $this->allArticles = $articleModel->take($numberArticles);
        }else{
            $this->allArticles = $articleModel;
        }
        
        //$article = ArticleModel::where("slug->".$this->lang, $articleSlug)->first();

        if($this->allArticles){
            
            /*$this->articleExists = true;
            $this->articleTitle = $article->title;
            $this->articleBody = $article->body;*/

        }else{

            $this->articlesNotFoundTextTranslations = [
                "en" => trans('components/article-all-show.label-articles-not-found'), 
                "es" => trans('components/article-all-show.label-articles-not-found')
            ];

            /*$this->articleExists = false;
            $this->articleNotFoundText = $this->articleNotFoundTextTranslations[$this->lang];*/

        }

        $this->setSeo();

        
    }

    public function setSeo(){

        if(request()->routeIs('blog.show.en-es')) {
            seo()
                ->title(trans('components/article-all-show.page-seo-title'), env('APP_NAME'))
                ->description(trans('components/article-all-show.page-seo-description'))
                ->images(
                    env('APP_LOGO_1_PATH'),
                    env('APP_LOGO_2_PATH'),
                );
        }

        
        
    }

    public function render()
    {
        return view('livewire.blog/show');
    }
}
