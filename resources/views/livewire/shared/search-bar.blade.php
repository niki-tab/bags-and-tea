{{-- Desktop Version - Only the input field container --}}
<form wire:submit.prevent="search" class="relative w-3/5 pt-4" x-data="{ focused: false }" @click.outside="$wire.hideResults()">
    <img src="{{ asset('images/icons/vector_search_icon.svg') }}" 
        class="absolute left-5 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 mt-2" 
        alt="Search Icon">

    <input type="text" 
        wire:model.live.debounce.300ms="query"
        @focus="focused = true"
        @blur="setTimeout(() => focused = false, 200)"
        @keydown.enter.prevent="$wire.search()"
        class="text-sm h-10 placeholder:font-robotoCondensed placeholder:text-color-2 placeholder:font-light font-robotoCondensed w-full p-3 pl-12 border border-gray-300 focus:outline-none transition-all duration-200 {{ $showResults ? 'rounded-t-full border-b-0' : 'rounded-full' }}" 
        placeholder="{{ trans('components/header.placeholder-input-search-product') }}"
        autocomplete="off">

    @if($isLoading)
        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
            <div class="animate-spin rounded-full h-4 w-4 border-2 border-blue-400 border-t-transparent"></div>
        </div>
    @endif

    @if($showResults)
        <div class="absolute top-full left-0 w-full bg-white border border-gray-300 border-t-0 rounded-b-[2.5rem] shadow-lg z-[60] max-h-96 overflow-y-auto">
            
            @if(count($suggestions) > 0)
                <div class="p-3 border-b border-gray-100">
                    <p class="text-xs text-gray-500 font-medium mb-2">{{ trans('components/search-bar.suggestions') }}</p>
                    @foreach($suggestions as $suggestion)
                        <button type="button" wire:click="selectSuggestion({{ json_encode($suggestion) }})" 
                                class="block w-full text-left px-2 py-1 text-sm text-gray-700 hover:bg-gray-50 rounded">
                            {{ $suggestion }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if(count($results) > 0)
                <div class="p-3">
                    <p class="text-xs text-gray-500 font-medium mb-3">{{ trans('components/search-bar.products') }}</p>
                    @foreach($results as $product)
                        <a href="{{ $product['url'] }}" 
                           class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors duration-150">
                            @if($product['image'])
                                <img src="{{ $product['image'] }}" 
                                     alt="{{ $product['name'] }}" 
                                     class="w-12 h-12 object-cover rounded-lg mr-3 bg-gray-100">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product['name'] }}</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-500">{{ $product['brand'] }}</p>
                                    <div class="text-right">
                                        @if($product['discounted_price'])
                                            <span class="text-sm font-bold text-red-600">€{{ number_format($product['discounted_price'], 2) }}</span>
                                            <span class="text-xs text-gray-400 line-through ml-1">€{{ number_format($product['price'], 2) }}</span>
                                        @else
                                            <span class="text-sm font-bold text-gray-900">€{{ number_format($product['price'], 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if(count($results) === 0 && count($suggestions) === 0 && strlen(trim($query)) >= 2)
                <div class="p-4 text-center text-gray-500">
                    <p class="text-sm">{{ trans('components/search-bar.no-results') }}</p>
                </div>
            @endif
        </div>
    @endif
</form>