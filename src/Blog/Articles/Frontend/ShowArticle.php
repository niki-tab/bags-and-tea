<?php

namespace Src\Blog\Articles\Frontend;

use Livewire\Component;
use App\Models\ProductModel;
use App\Models\ProductSizeVariationModel;
use Src\Blog\Articles\Model\ArticleModel;
use App\Models\ProductQuantityVariationModel;
use App\Models\ProducSizeVariationQuantityVariationPriceModel;

class ShowArticle extends Component
{
    public $articleModel;
    public $articleTitle;
    public $articleMainImage;
    public $articleBody;
    public $lang;

    public bool $articleExists;
    public string $articleNotFoundText;
    public array $articleNotFoundTextTranslations;

    public function mount($articleSlug)
    {   
        $this->lang = app()->getLocale();
        
        $article = ArticleModel::where('slug', 'like', '%' . $articleSlug . '%')->first();

        if ($article) {
            $correctSlug = $article->getTranslation('slug', $this->lang);
            
            // If current slug doesn't match the correct one, redirect
            if ($articleSlug !== $correctSlug) {
                return redirect()->route('article.show.' . $this->lang, [
                    'articleSlug' => $correctSlug,
                    'locale' => $this->lang
                ]);
            }

            $this->articleModel = $article;
            $this->articleExists = true;
            $this->articleTitle = $article->title;
            $this->articleMainImage = $article->main_image;
            $this->articleBody = $this->removeEmptyPTags($article->body);
            $this->setSeo();
        } else {
            $this->articleNotFoundTextTranslations = [
                "en" => trans('components/article-show.label-article-not-found'), 
                "es" => trans('components/article-show.label-article-not-found')
            ];
            $this->articleExists = false;
            $this->articleNotFoundText = $this->articleNotFoundTextTranslations[$this->lang];
        }
    }

    public function setSeo(){

        if($this->articleModel){

            seo()
                ->title($this->articleModel->meta_title, env('APP_NAME'))
                ->description($this->articleModel->meta_description)
                ->images(
                    env('APP_LOGO_1_PATH'),
                        env('APP_LOGO_2_PATH'),
                );

        }
        
        
    }

    public function render()
    {
        return view('livewire.blog/articles/show');
    }

    function removeEmptyPTags($html)
    {
        // Return early if no HTML or no <p> tags
        if (empty($html) || strpos($html, '<p') === false) {
            return $html;
        }

        // Load HTML into DOMDocument
        $doc = new \DOMDocument();
        // Silence warnings from malformed HTML with @, add encoding to prevent UTF-8 issues
        @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        // Get all <p> tags
        $pTags = $doc->getElementsByTagName('p');

        // Iterate backwards to safely remove nodes
        for ($i = $pTags->length - 1; $i >= 0; $i--) {
            $p = $pTags->item($i);
            // Check if the <p> is empty (no text, only whitespace, or nbsp)
            $content = trim($p->textContent);
            if ($content === '' || $content === ' ') {
                $p->parentNode->removeChild($p);
            }
        }

        // Extract the cleaned HTML, removing DOCTYPE and <html><body> wrappers
        $body = $doc->getElementsByTagName('body')->item(0);
        $cleanedHtml = '';
        foreach ($body->childNodes as $node) {
            $cleanedHtml .= $doc->saveHTML($node);
        }

        return $cleanedHtml;
    }

}
