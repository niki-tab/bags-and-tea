<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductSizeVariationModel;
use App\Models\ProductQuantityVariationModel;
use App\Models\ProducSizeVariationQuantityVariationPriceModel;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class ProductDetail extends Component
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

    public function mount($productSlug)
    {   

        $this->lang = app()->getLocale();

        $this->product = ProductEloquentModel::where("slug->".$this->lang, $productSlug)->first();
        
        
        
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

        $productName = strtolower($this->product->name);
        $productFoodType = $this->product->food_type;

        if($this->lang == "en"){

            seo()
            ->title($productFoodType.": ".$productName, env('APP_NAME'))
            ->description('Buy fresh '.$productName.' from our marketplace with delivery in less than 24 hours. Support oyster farmers and fishers while enjoying sustainable seafood at the best price with Rutas Del Mar!')
            ->images(
                env('APP_LOGO_1_PATH'),
                    env('APP_LOGO_2_PATH'),
            );

        }else{

            seo()
            ->title($productFoodType.": ".$productName, env('APP_NAME'))
            ->description('Compra '.$productName.' frescas en nuestro marketplace con entrega en menos de 24 horas. Apoya a los ostricultores y pescadores mientras disfrutas de mariscos sostenibles al mejor precio con Rutas Del Mar!')
            ->images(
                env('APP_LOGO_1_PATH'),
                    env('APP_LOGO_2_PATH'),
            );

        }

        
        
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
