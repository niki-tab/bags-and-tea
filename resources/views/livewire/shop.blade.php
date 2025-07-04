<div>
    <div class="mx-4 md:mx-12 px-4 py-8 bg-white">
        {{-- Header Section --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl {{ preg_match('/\d/', $pageTitle) ? 'font-robotoCondensed' : 'font-[\'Lovera\']' }} text-color-2 mb-8 mt-6">
                {{ $pageTitle }}
            </h1>
            <p class="text-color-2 w-3/4 mx-auto robotoCondensed text-left">
                {{ $pageDescription }}
            </p>
        </div>


        {{-- Debug Section (Remove in production) --}}
        <div class="mb-4 p-4 bg-gray-100 rounded">
            <h4 class="font-bold mb-2">Debug Info:</h4>
            <p><strong>Products count:</strong> {{ count($products ?? []) }}</p>
            <p><strong>Filters count:</strong> {{ count($filters ?? []) }}</p>
            <p><strong>Filter options:</strong> {{ json_encode(array_keys($filterOptions ?? [])) }}</p>
            @if(isset($filterOptions['brand']))
                <p><strong>Brand options count:</strong> {{ count($filterOptions['brand']) }}</p>
                @if(count($filterOptions['brand']) > 0)
                    <p><strong>First brand:</strong> {{ $filterOptions['brand'][0]->getTranslation('name', app()->getLocale()) ?? 'No name' }}</p>
                @endif
            @endif
            @if(isset($filterOptions['material']))
                <p><strong>Material options count:</strong> {{ count($filterOptions['material']) }}</p>
                @if(count($filterOptions['material']) > 0)
                    <p><strong>First material:</strong> {{ $filterOptions['material'][0]['name'][app()->getLocale()] ?? ($filterOptions['material'][0]->getTranslation('name', app()->getLocale()) ?? 'No name') }}</p>
                @endif
            @endif
            @if(isset($filterOptions['category']))
                <p><strong>Category options count:</strong> {{ count($filterOptions['category']) }}</p>
            @endif
            <p><strong>Selected filters:</strong> {{ json_encode($selectedFilters) }}</p>
            <p><strong>Applied filters passed to use case:</strong> {{ json_encode($appliedFilters ?? []) }}</p>
            <p><strong>Selected sort by:</strong> {{ $selectedSortBy ?: 'None' }}</p>
            <p><strong>Price range min/max:</strong> {{ json_encode($priceRange) }}</p>
            @if(count($products) > 0 && isset($products[0]))
                <p><strong>First product brand_id:</strong> {{ $products[0]->brand_id ?? 'null' }}</p>
            @endif
        </div>

        {{-- Filter Section --}}
        <div class="mb-8 md:mb-20 mx-4 md:mx-16 lg:mx-32">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                {{-- Sort Order --}}
                <div class="relative">
                    <select wire:model.lazy="selectedSortBy" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white">
                        <option value="">{{ __('shop.sort_by') }}</option>
                        <option value="name_asc">{{ __('shop.name_asc') }}</option>
                        <option value="name_desc">{{ __('shop.name_desc') }}</option>
                        <option value="price_asc">{{ __('shop.price_asc') }}</option>
                        <option value="price_desc">{{ __('shop.price_desc') }}</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Dynamic Filters --}}
                @foreach($filterOptions as $filterKey => $options)
                    @if(!empty($options) && $filterKey !== 'price')
                        <div class="relative" x-data="{ open: false }" wire:key="filter-{{ $filterKey }}-{{ md5(serialize($selectedFilters)) }}">
                            <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                                @php
                                    $selectedCount = isset($selectedFilters[$filterKey]) ? count($selectedFilters[$filterKey]) : 0;
                                    $filterLabel = __('shop.' . $filterKey, [], app()->getLocale());
                                    if ($filterLabel === 'shop.' . $filterKey) {
                                        // Fallback to capitalized filter key if translation doesn't exist
                                        $filterLabel = ucfirst(str_replace('-', ' ', $filterKey));
                                    }
                                @endphp
                                @if($selectedCount > 0)
                                    {{ $selectedCount }} {{ $filterLabel }}(s)
                                @else
                                    {{ $filterLabel }}
                                @endif
                            </button>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto min-w-[200px]">
                                @foreach($options as $option)
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        @php
                                            // Find the filter configuration for this filterKey
                                            $currentFilter = null;
                                            foreach($filters as $filter) {
                                                $config = is_array($filter->config) ? $filter->config : json_decode($filter->config, true);
                                                $configFilterKey = ($filter->type === 'category' || $filter->type === 'attribute') && !empty($config['filter_slug']) 
                                                    ? $config['filter_slug'] 
                                                    : $filter->type;
                                                if ($configFilterKey === $filterKey) {
                                                    $currentFilter = $filter;
                                                    break;
                                                }
                                            }
                                            
                                            // Use slug for all filters to have user-friendly URLs
                                            $optionValue = is_object($option) ? $option->id : $option['id']; // Default fallback
                                            
                                            if (is_object($option)) {
                                                // Check if it's a model with translatable slug using Spatie
                                                if (method_exists($option, 'getTranslation') && property_exists($option, 'slug')) {
                                                    // Use Spatie's getTranslation method for current locale, fallback to English
                                                    $optionValue = $option->getTranslation('slug', app()->getLocale()) ?: $option->getTranslation('slug', 'en') ?: $option->id;
                                                } elseif (property_exists($option, 'slug') && !empty($option->slug)) {
                                                    // Handle direct slug property
                                                    $optionValue = $option->slug;
                                                }
                                            } elseif (is_array($option)) {
                                                // Handle array format
                                                if (isset($option['slug']) && !empty($option['slug'])) {
                                                    if (is_array($option['slug'])) {
                                                        $locale = app()->getLocale();
                                                        $optionValue = $option['slug'][$locale] ?? $option['slug']['en'] ?? $option['id'];
                                                    } else {
                                                        $optionValue = $option['slug'];
                                                    }
                                                }
                                            }
                                        @endphp
                                        @php
                                            // Check if this option is selected by converting back to ID for comparison
                                            $isSelected = false;
                                            if (isset($selectedFilters[$filterKey])) {
                                                // Convert the option slug back to ID to check against selected filters (which store IDs internally)
                                                $optionId = is_object($option) ? $option->id : $option['id'];
                                                $isSelected = in_array($optionId, $selectedFilters[$filterKey]);
                                            }
                                        @endphp
                                        <input type="checkbox" 
                                               wire:change="toggleFilter('{{ $filterKey }}', '{{ $optionValue }}')"
                                               @if($isSelected) checked @endif
                                               wire:key="checkbox-{{ $filterKey }}-{{ $optionValue }}-{{ $isSelected ? 'checked' : 'unchecked' }}"
                                               class="mr-2">
                                        <span class="text-sm">
                                            @if(is_object($option) && method_exists($option, 'getTranslation'))
                                                {{ $option->getTranslation('name', app()->getLocale()) }}
                                            @elseif(is_object($option) && property_exists($option, 'name'))
                                                @if(is_array($option->name))
                                                    {{ $option->name[app()->getLocale()] ?? $option->name['en'] ?? 'Unknown' }}
                                                @else
                                                    {{ $option->name }}
                                                @endif
                                            @elseif(is_array($option) && isset($option['name']))
                                                @if(is_array($option['name']))
                                                    {{ $option['name'][app()->getLocale()] ?? $option['name']['en'] ?? 'Unknown' }}
                                                @else
                                                    {{ $option['name'] }}
                                                @endif
                                            @else
                                                Unknown
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                {{-- Price Filter (Special handling) --}}
                @if(isset($filterOptions['price']) && !empty($filterOptions['price']))
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                        @php
                            $selectedPriceCount = isset($selectedFilters['price']) ? count($selectedFilters['price']) : 0;
                        @endphp
                        @if($selectedPriceCount > 0)
                            {{ $selectedPriceCount }} {{ __('shop.price') }}(s)
                        @else
                            {{ __('shop.price') }}
                        @endif
                    </button>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto min-w-[200px]">
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" 
                                   wire:change="toggleFilter('price', '0-100')"
                                   @if(isset($selectedFilters['price']) && in_array('0-100', $selectedFilters['price'])) checked @endif
                                   class="mr-2">
                            <span class="text-sm">€0 - €100</span>
                        </label>
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" 
                                   wire:change="toggleFilter('price', '100-500')"
                                   @if(isset($selectedFilters['price']) && in_array('100-500', $selectedFilters['price'])) checked @endif
                                   class="mr-2">
                            <span class="text-sm">€100 - €500</span>
                        </label>
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" 
                                   wire:change="toggleFilter('price', '500-1000')"
                                   @if(isset($selectedFilters['price']) && in_array('500-1000', $selectedFilters['price'])) checked @endif
                                   class="mr-2">
                            <span class="text-sm">€500 - €1,000</span>
                        </label>
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" 
                                   wire:change="toggleFilter('price', '1000-2000')"
                                   @if(isset($selectedFilters['price']) && in_array('1000-2000', $selectedFilters['price'])) checked @endif
                                   class="mr-2">
                            <span class="text-sm">€1,000 - €2,000</span>
                        </label>
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" 
                                   wire:change="toggleFilter('price', '2000+')"
                                   @if(isset($selectedFilters['price']) && in_array('2000+', $selectedFilters['price'])) checked @endif
                                   class="mr-2">
                            <span class="text-sm">€2,000+</span>
                        </label>
                    </div>
                </div>
                @endif




                
            </div>
        </div>

        {{-- Active Filters Display --}}
        @php
            $activeFilters = $this->getActiveFilters();
        @endphp
        
        @if(!empty($activeFilters))
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2">
                @foreach($activeFilters as $filter)
                    <div class="inline-flex items-center bg-background-color-4 hover:bg-background-color-4/80 border border-background-color-4 rounded-full px-3 py-1.5 text-sm text-color-2 font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <span>{{ $filter['label'] }}</span>
                        <button 
                            wire:click="removeFilter('{{ $filter['type'] }}', '{{ $filter['value'] }}')"
                            class="ml-2 p-0.5 text-color-2/70 hover:text-color-2 hover:bg-color-2/20 rounded-full focus:outline-none focus:ring-2 focus:ring-color-2/50 focus:ring-offset-1 transition-all duration-150"
                            title="Remove {{ $filter['label'] }} filter"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
                
                {{-- Clear All Filters --}}
                @if(count($activeFilters) > 1)
                    <button 
                        wire:click="clearFilters"
                        class="inline-flex items-center bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-full px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 font-medium transition-all duration-200 shadow-sm hover:shadow-md ml-2"
                        title="Clear all filters"
                    >
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        {{ __('shop.clear_all') }}
                    </button>
                @endif
            </div>
        </div>
        @endif

        {{-- Loading State --}}
        @if($loading)
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
            </div>
        @endif

        {{-- Products Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6" wire:loading.remove wire:key="products-container-{{ $selectedSortBy ?: 'none' }}-{{ md5(serialize($selectedFilters)) }}">
            @foreach($products as $index => $product)
                <div wire:key="product-{{ $product->id }}-sort-{{ $selectedSortBy ?: 'none' }}" class="bg-white rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    {{-- Product Image --}}
                    <div class="relative bg-gray-100 h-64">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Image indicators (dots) --}}
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <div class="w-2 h-2 bg-gray-800 rounded-full"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                        </div>
                    </div>

                    {{-- Product Info --}}
                    <div class="p-4">
                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">
                            @if($product->brand)
                                {{ $product->brand->getTranslation('name', app()->getLocale()) }}
                            @else
                                {{ __('MARCA') }}
                            @endif
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">
                            {{ $product->getTranslation('name', app()->getLocale()) ?? __('Modelo') }}
                        </h3>
                        <div class="text-lg font-bold text-gray-900">
                            €{{ number_format($product->price ?? 450, 0) }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        @if(empty($products) && !$loading)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('shop.no_products_found') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('shop.adjust_filters_message') }}</p>
            </div>
        @endif

        {{-- Pagination Component --}}
        @if(!$loading && !empty($products))
            @livewire('shared/pagination', [
                'currentPage' => $currentPage,
                'totalItems' => $totalProducts,
                'perPage' => $perPage,
                'paginationClass' => 'mx-4 md:mx-16 lg:mx-32'
            ], key('pagination-' . $totalProducts . '-' . $currentPage . '-' . md5(json_encode($selectedFilters))))
        @endif
    </div>
    <div class="bg-color-4 py-16 md:py-28 px-14 md:px-32">
            <h2 class="text-center md:text-left text-xl font-bold robotoCondensed text-color-2">
                @if(app()->getLocale() === 'es')
                    Descripción 2
                @else
                    Description 2
                @endif
            </h2>
            <p class="text-center md:text-left text-sm robotoCondensed mt-8 text-color-2">
                {{ $pageDescription2 }}
            </p>
    </div>
</div>