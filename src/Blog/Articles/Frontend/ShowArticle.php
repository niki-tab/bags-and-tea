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
    public $product;

    public $productSizeVariations;

    public $productQuantityVariations;

    public $productHasQuantityVariation;

    public $productSizeQuantityVariationsPrice;
    
    public $selectedSize;

    public $quantityOptions;

    public $specificQuantity = 1;
    public $selectedQuantity;

    public $selectedQuantityId;
    public $specificPrice = 5;
    public $lang;

    public $test;
    public $articleModel;
    public $articleTitle;
    public $articleBody;

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
            $this->articleBody = $article->body;

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
}
