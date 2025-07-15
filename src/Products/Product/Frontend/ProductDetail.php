<?php

namespace Src\Products\Product\Frontend;

use Livewire\Component;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Products\Product\Infrastructure\Eloquent\ProductMediaModel;

class ProductDetail extends Component
{
    public $product;
    public $productImages = [];
    public $currentImageIndex = 0;
    public $lang;
    public $productSlug;
    
    // Product specifications
    public $specifications = [];

    public function mount($productSlug = null)
    {   
        $this->lang = app()->getLocale();
        $this->productSlug = $productSlug;

        if ($productSlug) {
            $this->product = ProductEloquentModel::with('media', 'brand', 'categories', 'attributes', 'quality')
                ->where("slug->".$this->lang, $productSlug)
                ->first();
            
            if($this->product){
                // Load product images from product_media table
                $this->productImages = $this->product->media->sortBy('sort_order')->values()->toArray();
                
                // Set up product specifications
                $this->setupSpecifications();
                
                // Set up SEO
                $this->setSeo();
            }
        }
    }
    
    public function setupSpecifications()
    {
        // Get Estado from quality relationship
        $estado = $this->product->quality ? $this->product->quality->getTranslation('name', $this->lang) : 'N/A';
        
        // Get Año from attributes - look for children of "Year of manufacture" parent
        $ano = 'N/A';
        if ($this->product->attributes) {
            // Find attributes that have a parent with year-related names
            foreach ($this->product->attributes as $attribute) {
                if ($attribute->parent_id) {
                    $parent = \Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel::find($attribute->parent_id);
                    if ($parent && (
                        stripos($parent->getTranslation('name', $this->lang), 'year') !== false ||
                        stripos($parent->getTranslation('name', $this->lang), 'año') !== false ||
                        stripos($parent->getTranslation('name', $this->lang), 'manufacture') !== false ||
                        stripos($parent->getTranslation('name', $this->lang), 'fabricación') !== false
                    )) {
                        $ano = $attribute->getTranslation('name', $this->lang);
                        break;
                    }
                }
            }
        }
        
        // Get Color from categories - look for children of "Color" parent
        $color = 'N/A';
        if ($this->product->categories) {
            foreach ($this->product->categories as $category) {
                if ($category->parent_id) {
                    $parent = \Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel::find($category->parent_id);
                    if ($parent && (
                        stripos($parent->getTranslation('name', $this->lang), 'color') !== false ||
                        stripos($parent->getTranslation('name', $this->lang), 'colour') !== false
                    )) {
                        $color = $category->getTranslation('name', $this->lang);
                        break;
                    }
                }
            }
        }
        
        // Get Tamaño from product dimensions (width x height x depth)
        $width = $this->product->width ?? 0;
        $height = $this->product->height ?? 0;
        $depth = $this->product->depth ?? 0;
        $tamano = "{$width} x {$height} x {$depth}";
        
        $this->specifications = [
            'estado' => $estado,
            'ano' => $ano,
            'color' => $color,
            'tamano' => $tamano
        ];
    }
    
    public function setCurrentImage($index)
    {
        $this->currentImageIndex = $index;
    }
    
    public function previousImage()
    {
        $this->currentImageIndex = $this->currentImageIndex > 0 
            ? $this->currentImageIndex - 1 
            : count($this->productImages) - 1;
    }
    
    public function nextImage()
    {
        $this->currentImageIndex = $this->currentImageIndex < count($this->productImages) - 1 
            ? $this->currentImageIndex + 1 
            : 0;
    }

    public function setSeo(){
        if(!$this->product) return;
        
        $productName = $this->product->getTranslation('name', $this->lang);
        $productDescription = $this->product->getTranslation('description_1', $this->lang);
        $brandName = $this->product->brand ? $this->product->brand->name : '';

        if($this->lang == "en"){
            seo()
            ->title($brandName.' '.$productName, env('APP_NAME'))
            ->description($productDescription ?: 'Luxury handbag from '.$brandName.' - authentic pre-owned designer bags at Bags & Tea')
            ->images(
                !empty($this->productImages) ? asset($this->productImages[0]['file_path']) : env('APP_LOGO_1_PATH'),
                env('APP_LOGO_2_PATH'),
            );
        }else{
            seo()
            ->title($brandName.' '.$productName, env('APP_NAME'))
            ->description($productDescription ?: 'Bolso de lujo de '.$brandName.' - bolsos de diseñador auténticos de segunda mano en Bags & Tea')
            ->images(
                !empty($this->productImages) ? asset($this->productImages[0]['file_path']) : env('APP_LOGO_1_PATH'),
                env('APP_LOGO_2_PATH'),
            );
        }
    }

    public function render()
    {
        return view('livewire.products.product');
    }
}