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

    private function translateQueryParameters($queryString, $targetLanguage)
    {
        if (empty($queryString)) {
            return '';
        }

        parse_str($queryString, $params);
        $translatedParams = [];

        \Log::info('Translating query parameters', [
            'original_query' => $queryString,
            'parsed_params' => $params,
            'target_language' => $targetLanguage
        ]);

        foreach ($params as $key => $value) {
            $translatedKey = $this->translateFilterKey($key, $targetLanguage);
            $translatedValue = $this->translateFilterValues($key, $value, $targetLanguage);
            $translatedParams[$translatedKey] = $translatedValue;

            \Log::info('Parameter translation', [
                'original_key' => $key,
                'translated_key' => $translatedKey,
                'original_value' => $value,
                'translated_value' => $translatedValue
            ]);
        }

        $result = http_build_query($translatedParams);

        \Log::info('Final query result', [
            'translated_params' => $translatedParams,
            'final_query' => $result
        ]);

        return $result;
    }

    private function translateFilterKey($filterKey, $targetLanguage)
    {
        // Static system parameter mappings
        $staticMappings = [
            'en' => [
                'search' => 'search',
                'buscar' => 'search',
                'sort' => 'sort',
                'ordenar' => 'sort',
                'page' => 'page',
                'pagina' => 'page',
                'price' => 'price',
                'precio' => 'price',
                'quality' => 'quality',
                'condicion' => 'quality',
            ],
            'es' => [
                'search' => 'buscar',
                'buscar' => 'buscar',
                'sort' => 'ordenar',
                'ordenar' => 'ordenar',
                'page' => 'pagina',
                'pagina' => 'pagina',
                'price' => 'precio',
                'precio' => 'precio',
                'quality' => 'condicion',
                'condicion' => 'condicion',
            ]
        ];

        // Check static mappings first
        if (isset($staticMappings[$targetLanguage][$filterKey])) {
            return $staticMappings[$targetLanguage][$filterKey];
        }

        // Get dynamic filter key mappings from database
        return $this->getDynamicFilterKeyMapping($filterKey, $targetLanguage);
    }

    private function getDynamicFilterKeyMapping($filterKey, $targetLanguage)
    {
        // For now, we'll use a practical static mapping for dynamic filter keys
        // This could be extended in the future to store localized filter slugs in the database
        // by adding a 'localized_slugs' JSON field to the shop_filters table

        $dynamicFilterMappings = [
            'en' => [
                'bag-type' => 'bag-type',
                'tipo-de-bolso' => 'bag-type',
                'material' => 'material',
                'color' => 'color',
                'size' => 'size',
                'talla' => 'size',
                'bags' => 'bags',
                'bolsos' => 'bags',
                'year-of-manufacture' => 'year-of-manufacture',
                'ano-de-fabricacion' => 'year-of-manufacture',
                'category' => 'category',
                'categoria' => 'category',
                'attribute' => 'attribute',
                'atributo' => 'attribute',
            ],
            'es' => [
                'bag-type' => 'tipo-de-bolso',
                'tipo-de-bolso' => 'tipo-de-bolso',
                'material' => 'material',
                'color' => 'color',
                'size' => 'talla',
                'talla' => 'talla',
                'bags' => 'bolsos',
                'bolsos' => 'bolsos',
                'year-of-manufacture' => 'ano-de-fabricacion',
                'ano-de-fabricacion' => 'ano-de-fabricacion',
                'category' => 'categoria',
                'categoria' => 'categoria',
                'attribute' => 'atributo',
                'atributo' => 'atributo',
            ]
        ];

        // Check if this filter key exists in our mappings
        if (isset($dynamicFilterMappings[$targetLanguage][$filterKey])) {
            return $dynamicFilterMappings[$targetLanguage][$filterKey];
        }

        // Verify that this is actually a valid filter from the database
        try {
            $shopFilters = DB::table('shop_filters')
                ->where('is_active', true)
                ->get();

            foreach ($shopFilters as $filter) {
                $config = json_decode($filter->config, true);
                if (isset($config['filter_slug']) && $config['filter_slug'] === $filterKey) {
                    // This is a valid filter from the database, but we don't have a translation
                    // Log this for future enhancement
                    \Log::info("Filter key '{$filterKey}' found in database but no translation available for '{$targetLanguage}'");
                    break;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to verify filter key in database: ' . $e->getMessage());
        }

        // Fallback: return original key
        return $filterKey;
    }

    private function translateFilterValues($filterKey, $value, $targetLanguage)
    {
        // Handle comma-separated values
        $values = is_array($value) ? $value : explode(',', $value);
        $translatedValues = [];

        foreach ($values as $singleValue) {
            $translatedValue = $this->translateSingleFilterValue($filterKey, trim($singleValue), $targetLanguage);
            $translatedValues[] = $translatedValue;
        }

        return implode(',', $translatedValues);
    }

    private function translateSingleFilterValue($filterKey, $value, $targetLanguage)
    {
        try {
            // Handle quality filter (uses codes, not slugs)
            if ($filterKey === 'quality') {
                $quality = DB::table('qualities')
                    ->where('code', $value)
                    ->first();

                if ($quality && $quality->name) {
                    $names = json_decode($quality->name, true);
                    return $names[$targetLanguage] ?? $value;
                }
            }

            // Handle category-based filters (bag-type, material, color, etc.)
            // These use category slugs as values
            $categoryTranslations = $this->getCategoryTranslatedSlugs($value);
            return $categoryTranslations[$targetLanguage] ?? $value;

        } catch (\Exception $e) {
            \Log::warning('Failed to translate filter value: ' . $e->getMessage());
            return $value;
        }
    }

    public function render()
    {
        return view('livewire.shared/language-selector');
    }
}