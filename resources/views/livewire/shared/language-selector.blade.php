@php
    // Build base route URLs (without query strings)
    $routeParamsEnglish = array_intersect_key($paramsEnglish, array_flip(['locale', 'slug', 'productSlug', 'articleSlug', 'order_number']));
    $routeParamsSpanish = array_intersect_key($paramsSpanish, array_flip(['locale', 'slug', 'productSlug', 'articleSlug', 'order_number']));

    $baseUrlEnglish = route($routeEnglish, $routeParamsEnglish);
    $baseUrlSpanish = route($routeSpanish, $routeParamsSpanish);

    // Get translation mappings for JavaScript
    $filterSlugMap = [];
    $valueSlugMap = [];

    $shopFilters = \DB::table('shop_filters')->where('is_active', true)->get();
    foreach ($shopFilters as $filter) {
        if ($filter->slug) {
            $slugs = json_decode($filter->slug, true);
            if ($slugs && isset($slugs['en']) && isset($slugs['es'])) {
                $filterSlugMap['en_to_es'][$slugs['en']] = $slugs['es'];
                $filterSlugMap['es_to_en'][$slugs['es']] = $slugs['en'];
            }
        }
    }

    $categories = \DB::table('categories')->whereNotNull('slug')->get();
    foreach ($categories as $category) {
        $slugs = json_decode($category->slug, true);
        if ($slugs && isset($slugs['en']) && isset($slugs['es'])) {
            $valueSlugMap['en_to_es'][$slugs['en']] = $slugs['es'];
            $valueSlugMap['es_to_en'][$slugs['es']] = $slugs['en'];
        }
    }

    $attributes = \DB::table('attributes')->whereNotNull('slug')->get();
    foreach ($attributes as $attribute) {
        $slugs = json_decode($attribute->slug, true);
        if ($slugs && isset($slugs['en']) && isset($slugs['es'])) {
            $valueSlugMap['en_to_es'][$slugs['en']] = $slugs['es'];
            $valueSlugMap['es_to_en'][$slugs['es']] = $slugs['en'];
        }
    }
@endphp

<script>
window.translateShopUrl = function(baseUrl, targetLang) {
    const filterSlugMap = @json($filterSlugMap);
    const valueSlugMap = @json($valueSlugMap);

    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);
    const translated = new URLSearchParams();

    const direction = targetLang === 'es' ? 'en_to_es' : 'es_to_en';

    params.forEach((value, key) => {
        if (['page', 'sort', 'search', 'locale'].includes(key)) {
            translated.set(key, value);
            return;
        }

        const translatedKey = filterSlugMap[direction]?.[key] || key;
        const values = value.split(',');
        const translatedValues = values.map(val => valueSlugMap[direction]?.[val] || val);

        translated.set(translatedKey, translatedValues.join(','));
    });

    const queryString = translated.toString();
    return queryString ? baseUrl + '?' + queryString : baseUrl;
};
</script>

<div class="ml-2 relative inline-block text-left">
    <button type="button"
            class="inline-flex items-center justify-center py-2 text-lg font-medium text-color-2 hover:bg-opacity-80 focus:outline-none"
            @click="open = !open"
            x-data="{ open: false }"
            @click.away="open = false">
        {{ strtoupper(app()->getLocale()) }}
        <svg class="-mr-1 ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>

        <div x-show="open"
             class="absolute right-0 mt-2 w-24 rounded-md shadow-lg bg-background-color-4 ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#"
                   onclick="event.preventDefault(); window.location.href = window.translateShopUrl('{{ $baseUrlEnglish }}', 'en');"
                   class="text-color-2 block px-4 py-2 text-sm hover:bg-gray-100">EN</a>
                <a href="#"
                   onclick="event.preventDefault(); window.location.href = window.translateShopUrl('{{ $baseUrlSpanish }}', 'es');"
                   class="text-color-2 block px-4 py-2 text-sm hover:bg-gray-100">ES</a>
            </div>
        </div>
    </button>
</div>