<?php

namespace Src\Shared\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

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
        }elseif($this->currentRouteName == "checkout.success.es" || $this->currentRouteName == "checkout.success.en"){
            $orderNumber = request()->route('order_number');

            $this->paramsSpanish = ["locale" => "es", 'order_number' => $orderNumber];
            $this->paramsEnglish = ["locale" => "en", 'order_number' => $orderNumber];
        }elseif($this->currentRouteName == "shop.show.es" || $this->currentRouteName == "shop.show.en"){
            // Handle shop routes with optional slug parameter
            $slug = request()->route('slug');

            $this->paramsSpanish = ["locale" => "es"];
            $this->paramsEnglish = ["locale" => "en"];

            if ($slug) {
                // Find the category and get translated slugs
                $translatedSlugs = $this->getCategoryTranslatedSlugs($slug);

                $spanishSlug = $translatedSlugs['es'] ?? $slug;
                $englishSlug = $translatedSlugs['en'] ?? $slug;

                $this->paramsSpanish['slug'] = $spanishSlug;
                $this->paramsEnglish['slug'] = $englishSlug;
            }
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
            // Find the product by searching in both language slug fields using raw DB query
            $product = DB::table('products')
                ->where(function ($query) use ($currentSlug) {
                    $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', [$currentSlug])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.es")) = ?', [$currentSlug]);
                })
                ->first();

            if ($product && $product->slug) {
                // If slug is stored as JSON, decode it
                $slugs = json_decode($product->slug, true);

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

    private function getCategoryTranslatedSlugs($currentSlug)
    {
        try {
            // Find the category by searching in both language slug fields using raw DB query
            $category = DB::table('categories')
                ->where(function ($query) use ($currentSlug) {
                    $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', [$currentSlug])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.es")) = ?', [$currentSlug]);
                })
                ->first();

            if ($category && $category->slug) {
                // If slug is stored as JSON, decode it
                $slugs = json_decode($category->slug, true);

                return [
                    'en' => $slugs['en'] ?? $currentSlug,
                    'es' => $slugs['es'] ?? $currentSlug
                ];
            }
        } catch (\Exception $e) {
            // If anything fails, return the current slug for both languages
            \Log::warning('Failed to get translated category slugs: ' . $e->getMessage());
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