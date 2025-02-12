<?php

namespace Src\Blog\Articles\Frontend;

use Livewire\Component;
use Src\Blog\Articles\Model\ArticleModel;

class ShowAllArticle extends Component
{
    public $lang;

    public $allArticles;
    public array $articlesNotFoundTextTranslations;

    public function mount()
    {   
        
        $this->lang = app()->getLocale();

        $articleModel = ArticleModel::where('state', 'published')->get();

        $this->allArticles = $articleModel;
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

        seo()
        ->title(trans('components/article-all-show.page-seo-title'), env('APP_NAME'))
        ->description(trans('components/article-all-show.page-seo-description'))
        ->images(
            env('APP_LOGO_1_PATH'),
                env('APP_LOGO_2_PATH'),
        );

        
        
    }

    public function render()
    {
        return view('livewire.blog/show');
    }
}
