<div class="container mx-auto px-4 py-8">
    {{-- Header Section --}}
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            {{ __('shop.page_title') }}
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            {{ __('shop.page_description') }}
        </p>
    </div>

    {{-- Breadcrumb Navigation --}}
    <div class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <span>{{ __('Modelo bolso #1') }}</span>
        <span>|</span>
        <span>{{ __('Lorem ipsum dolor') }}</span>
        <span>|</span>
        <span>{{ __('Lorem ipsum dolor') }}</span>
        <span>|</span>
        <span>{{ __('Lorem ipsum dolor') }}</span>
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
        <p><strong>Selected brand:</strong> {{ is_array($selectedBrand) ? implode(', ', $selectedBrand) : ($selectedBrand ?: 'None') }}</p>
        <p><strong>Selected category:</strong> {{ is_array($selectedCategory) ? implode(', ', $selectedCategory) : ($selectedCategory ?: 'None') }}</p>
        <p><strong>Selected attribute:</strong> {{ is_array($selectedAttribute) ? implode(', ', $selectedAttribute) : ($selectedAttribute ?: 'None') }}</p>
        <p><strong>Selected quality:</strong> {{ is_array($selectedQuality) ? implode(', ', $selectedQuality) : ($selectedQuality ?: 'None') }}</p>
        <p><strong>Selected price range:</strong> {{ is_array($selectedPriceRange) ? implode(', ', $selectedPriceRange) : ($selectedPriceRange ?: 'None') }}</p>
        <p><strong>Applied filters passed to use case:</strong> {{ json_encode($appliedFilters ?? []) }}</p>
        <p><strong>Selected sort by:</strong> {{ $selectedSortBy ?: 'None' }}</p>
        <p><strong>Price range min/max:</strong> {{ json_encode($priceRange) }}</p>
        @if(count($products) > 0 && isset($products[0]))
            <p><strong>First product brand_id:</strong> {{ $products[0]->brand_id ?? 'null' }}</p>
        @endif
    </div>

    {{-- Filter Section --}}
    <div class="mb-8">
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

            {{-- Brand Filter --}}
            @if(isset($filterOptions['brand']) && count($filterOptions['brand']) > 0)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                    @if(count($selectedBrand) > 0)
                        {{ count($selectedBrand) }} {{ __('shop.brand') }}(s)
                    @else
                        {{ __('shop.brand') }}
                    @endif
                </button>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto min-w-[200px]">
                    @foreach($filterOptions['brand'] as $brand)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" wire:model.lazy="selectedBrand" value="{{ $brand->id }}" class="mr-2">
                            <span class="text-sm">{{ $brand->getTranslation('name', app()->getLocale()) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Category Filter --}}
            @if(isset($filterOptions['category']) && count($filterOptions['category']) > 0)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                    @if(count($selectedCategory) > 0)
                        {{ count($selectedCategory) }} {{ __('shop.category') }}(s)
                    @else
                        {{ __('shop.category') }}
                    @endif
                </button>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto min-w-[200px]">
                    @foreach($filterOptions['category'] as $category)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" wire:model="selectedCategory" value="{{ $category->id }}" class="mr-2">
                            <span class="text-sm">{{ $category->getTranslation('name', app()->getLocale()) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quality Filter --}}
            @if(isset($filterOptions['quality']) && count($filterOptions['quality']) > 0)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                    @if(count($selectedQuality) > 0)
                        {{ count($selectedQuality) }} {{ __('shop.condition') }}(s)
                    @else
                        {{ __('shop.condition') }}
                    @endif
                </button>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto min-w-[200px]">
                    @foreach($filterOptions['quality'] as $quality)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" wire:model="selectedQuality" value="{{ $quality['id'] }}" class="mr-2">
                            <span class="text-sm">{{ $quality['name'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Attribute Filters (Size) --}}
            @if(isset($filterOptions['attribute']) && count($filterOptions['attribute']) > 0)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                    @if(count($selectedAttribute) > 0)
                        {{ count($selectedAttribute) }} {{ __('shop.size') }}(s)
                    @else
                        {{ __('shop.size') }}
                    @endif
                </button>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div x-show="open" @click.away="open = false" class="absolute z-10 mt-1 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto min-w-[200px]">
                    @foreach($filterOptions['attribute'] as $attribute)
                        <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <input type="checkbox" wire:model="selectedAttribute" value="{{ $attribute->id }}" class="mr-2">
                            <span class="text-sm">{{ $attribute->getTranslation('name', app()->getLocale()) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Price Filter --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="appearance-none border border-gray-300 rounded px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-gray-400 bg-white min-w-[120px] text-left">
                    @if(count($selectedPriceRange) > 0)
                        {{ count($selectedPriceRange) }} {{ __('shop.price') }}(s)
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
                        <input type="checkbox" wire:model.lazy="selectedPriceRange" value="0-100" class="mr-2">
                        <span class="text-sm">€0 - €100</span>
                    </label>
                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" wire:model.lazy="selectedPriceRange" value="100-500" class="mr-2">
                        <span class="text-sm">€100 - €500</span>
                    </label>
                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" wire:model.lazy="selectedPriceRange" value="500-1000" class="mr-2">
                        <span class="text-sm">€500 - €1,000</span>
                    </label>
                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" wire:model.lazy="selectedPriceRange" value="1000-2000" class="mr-2">
                        <span class="text-sm">€1,000 - €2,000</span>
                    </label>
                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" wire:model.lazy="selectedPriceRange" value="2000+" class="mr-2">
                        <span class="text-sm">€2,000+</span>
                    </label>
                </div>
            </div>




            {{-- Clear Filters Button --}}
            <button wire:click="clearFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                {{ __('shop.clear_filters') }}
            </button>

            {{-- View Toggle --}}
            <div class="ml-auto flex space-x-2">
                <button class="p-2 border border-gray-300 rounded hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </button>
                <button class="p-2 border border-gray-300 rounded hover:bg-gray-100 bg-gray-100">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Loading State --}}
    @if($loading)
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
        </div>
    @endif

    {{-- Products Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" wire:loading.remove wire:key="products-container-{{ $selectedSortBy ?: 'none' }}-{{ $urlPrice ?: 'none' }}-{{ $urlBrands ?: 'none' }}-{{ $urlCategories ?: 'none' }}-{{ $urlAttributes ?: 'none' }}-{{ $urlQualities ?: 'none' }}">
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
</div>