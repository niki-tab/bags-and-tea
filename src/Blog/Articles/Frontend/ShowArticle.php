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

        $article = ArticleModel::where("slug->".$this->lang, $articleSlug)
                                    ->first();

        $this->articleModel = $article;
        
        if($article){
            
            $this->articleExists = true;
            $this->articleTitle = $article->title;
            $this->articleMainImage = $article->main_image;
            $this->articleBody = $this->removeEmptyPTags($article->body);

            $this->setSeo();

        }else{

            $this->articleNotFoundTextTranslations = [
                "en" => trans('components/article-show.label-article-not-found'), 
                "es" => trans('components/article-show.label-article-not-found')
            ];

            $this->articleExists = false;
            $this->articleNotFoundText = $this->articleNotFoundTextTranslations[$this->lang];

        }


        
    }

    public function updateSelectedSize($value)
    {   

        /*$this->test = [
            ['id' => 1, 'quantity_name' => 'Smal2l'.rand(1,10), 'order' => 1],
            ['id' => 2, 'quantity_name' => 'Medium2'.rand(1,10), 'order' => 2],
            ['id' => 3, 'quantity_name' => 'Large2'.rand(1,10), 'order' => 3],
            ['id' => 4, 'quantity_name' => 'Largexxl2'.rand(1,10), 'order' => 4],
        ];*/
        //$this->test[] = ['id' => 4, 'quantity_name' => 'Largexxl2'.rand(1,10), 'order' => 4];

        //$this->test = ProductQuantityVariationModel::take(2)->get();
        //dd($this->test[])
        $this->test = array();

        $producSizeVariationQuantityVariationPriceModel = ProducSizeVariationQuantityVariationPriceModel::where(
            [
                ["product_size_variation_id", $value],
            ]
        )->get();
        //dd($producSizeVariationQuantityVariationPriceModel->toArray());
        foreach ($producSizeVariationQuantityVariationPriceModel as $producSizeVariationQuantityVariationPriceModelIndividual){
            
            $productQuantityVariationModel = ProductQuantityVariationModel::where("id", $producSizeVariationQuantityVariationPriceModelIndividual->product_quantity_variation_id)->first();
            //$quantityOptions[$productQuantityVariationModel->order] = $productQuantityVariationModel->id;

            if ($productQuantityVariationModel) {

                $this->test[] = [
                    'id' => $productQuantityVariationModel->id,
                    'quantity_name' => $productQuantityVariationModel->quantity_name, // Append a random number to the quantity name
                    'order' => $productQuantityVariationModel->order, // Assuming the order corresponds to the ID
                ];

            }

        }

        if($this->productHasQuantityVariation == true){

            $this->test = collect($this->test)->sortBy('order')->values()->all();
            
            $producSizeVariationQuantityVariationPriceModel = ProducSizeVariationQuantityVariationPriceModel::where(
                [
                    ["product_size_variation_id", $this->selectedSize],
                    ["product_quantity_variation_id", $this->test[0]["id"]],

                ]
            )->first();

            if($producSizeVariationQuantityVariationPriceModel){

                $this->specificPrice = $producSizeVariationQuantityVariationPriceModel->sale_price;
                $this->selectedQuantity = $producSizeVariationQuantityVariationPriceModel->product_quantity_variation_id;
            }

        }else{

            $this->specificPrice = $producSizeVariationQuantityVariationPriceModel[0]->sale_price;

        }

    }

    public function updateSelectedQuanitity($value)
    {   
        //dd($this->test);
        $producSizeVariationQuantityVariationPriceModel = ProducSizeVariationQuantityVariationPriceModel::where(
            [
                ["product_size_variation_id", $this->selectedSize],
                ["product_quantity_variation_id", $value],

            ]
        )->first();

        if($producSizeVariationQuantityVariationPriceModel){
            $this->specificPrice = $producSizeVariationQuantityVariationPriceModel->sale_price;
        }

        $this->selectedQuantity = $value;

        
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
