<?php

namespace Src\Shared\Frontend;

use Livewire\Component;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

class LanguageSelector extends Component
{
    public $currentLanguage;
    public $currentRouteName;
    public $routeSpanish;
    public $routeEnglish;
    public $paramsSpanish;
    public $paramsEnglish;

    public function mount()
    {
        $this->currentLanguage = app()->getLocale();
        $this->currentRouteName = request()->route()->getName();
        
        $locale = request()->segment(1);

        $isMultiLanguage = str_ends_with($this->currentRouteName, '.en-es');

        if ($isMultiLanguage) {

            $this->routeSpanish = $this->currentRouteName;
            $this->routeEnglish = $this->currentRouteName;

        } else {

            $routeParts = explode('.', $this->currentRouteName);

            // Remove the last element
            array_pop($routeParts);
    
            $this->routeSpanish  = $routeParts;
            $this->routeSpanish[] = "es";
            $this->routeSpanish = implode('.', $this->routeSpanish);
            
            $this->routeEnglish  = $routeParts;
            $this->routeEnglish[] = "en";
            $this->routeEnglish = implode('.', $this->routeEnglish);

        }

        if($this->currentRouteName == "article.show.es" || $this->currentRouteName == "article.show.en"){
            $this->paramsSpanish = ["locale" => "es", 'articleSlug' => request()->route('articleSlug')];
            $this->paramsEnglish = ["locale" => "en", 'articleSlug' => request()->route('articleSlug')];
        }elseif($this->currentRouteName == "product.show.es" || $this->currentRouteName == "product.show.en"){
            $productSlug = request()->route('productSlug');
            
            // Find the product and get translated slugs
            $translatedSlugs = $this->getProductTranslatedSlugs($productSlug);
            
            $spanishSlug = $translatedSlugs['es'] ?? $productSlug;
            $englishSlug = $translatedSlugs['en'] ?? $productSlug;
            
            $this->paramsSpanish = ["locale" => "es", 'productSlug' => $spanishSlug];
            $this->paramsEnglish = ["locale" => "en", 'productSlug' => $englishSlug];
        }else{
            $this->paramsSpanish = ["locale" => "es"];
            $this->paramsEnglish = ["locale" => "en"];
        }

        
    }

    public function switchLanguage()
    {
        $this->currentLanguage = $this->currentLanguage === 'en' ? 'es' : 'en';

        //return redirect()->route($newRouteName, ['locale' => $this->currentLanguage]);
    }

    private function getProductTranslatedSlugs($currentSlug)
    {
        try {
            // Find the product by searching in both language slug fields
            $product = ProductEloquentModel::where(function ($query) use ($currentSlug) {
                $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', [$currentSlug])
                      ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.es")) = ?', [$currentSlug]);
            })->first();

            if ($product && $product->slug) {
                // If slug is stored as JSON, decode it
                if (is_string($product->slug)) {
                    $slugs = json_decode($product->slug, true);
                } else {
                    $slugs = $product->slug;
                }

                return [
                    'en' => $slugs['en'] ?? $currentSlug,
                    'es' => $slugs['es'] ?? $currentSlug
                ];
            }
        } catch (\Exception $e) {
            // If anything fails, return the current slug for both languages
            \Log::warning('Failed to get translated product slugs: ' . $e->getMessage());
        }

        // Fallback: return current slug for both languages
        return [
            'en' => $currentSlug,
            'es' => $currentSlug
        ];
    }

    public function render()
    {
        return view('livewire.shared/language-selector');
    }
}