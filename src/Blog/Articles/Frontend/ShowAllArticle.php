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

        $articleModel = ArticleModel::all();

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


        
    }

    public function render()
    {
        return view('livewire.blog/show');
    }
}
