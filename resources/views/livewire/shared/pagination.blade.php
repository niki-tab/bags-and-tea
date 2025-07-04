<div>
    @if($this->hasMorePages)

{{-- Desktop: Combined navigation on same line --}}
<div class="hidden sm:flex items-center justify-between mt-8 md:mt-20 {{ $paginationClass }}">
    {{-- Previous Button - Left Side --}}
    @if($this->hasPreviousPage)
        <button 
            wire:click="previousPage"
            class="bg-white rounded-full px-4 py-3 hover:bg-gray-50 transition-all duration-200 group flex items-center">
            <svg class="h-5 w-5 text-color-2 group-hover:text-gray-800 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M17 10.75a.75.75 0 01-.75.75H6.5l1.2 2a.75.75 0 11-1.4.8L3.5 11.2a.75.75 0 010-.9l2.8-3.35a.75.75 0 111.4.8L6.5 10H16.25A.75.75 0 0117 10.75z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium text-color-2 group-hover:text-gray-800">{{ __('shop.previous') }}</span>
        </button>
    @else
        <div class="px-4 py-3 flex items-center">
            {{-- Invisible placeholder with same dimensions as Previous button --}}
            <svg class="h-5 w-5 mr-2 invisible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l1.158 1.96a.75.75 0 11-1.54.91L3.21 10.91a.75.75 0 010-.82l2.02-2.72a.75.75 0 011.54.91L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium invisible">{{ __('shop.previous') }}</span>
        </div>
    @endif

    {{-- Center Pagination --}}
    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        {{-- First Page (if not visible) --}}
        @if($this->showFirst)
            <button 
                wire:click="firstPage" 
                class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200 rounded-l-md">
                1
            </button>
            @if(!in_array(2, $this->visiblePages))
                <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                    ...
                </span>
            @endif
        @endif

        {{-- Visible Page Numbers --}}
        @foreach($this->visiblePages as $page)
            <button 
                wire:click="goToPage({{ $page }})" 
                class="relative inline-flex items-center px-3 py-2 border text-sm font-medium transition-colors duration-200
                       {{ $page === $currentPage 
                          ? 'z-10 bg-color-2 border-color-2 text-white' 
                          : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}
                       {{ $loop->first && !$this->showFirst ? 'rounded-l-md' : '' }}
                       {{ $loop->last && !$this->showLast ? 'rounded-r-md' : '' }}">
                {{ $page }}
            </button>
        @endforeach

        {{-- Last Page (if not visible) --}}
        @if($this->showLast)
            @if(!in_array($this->totalPages - 1, $this->visiblePages))
                <span class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500">
                    ...
                </span>
            @endif
            <button 
                wire:click="lastPage" 
                class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200 rounded-r-md">
                {{ $this->totalPages }}
            </button>
        @endif
    </nav>

    {{-- Next Button - Right Side --}}
    @if($this->hasNextPage)
        <button 
            wire:click="nextPage"
            class="bg-white rounded-full px-4 py-3 hover:bg-gray-50 transition-all duration-200 group flex items-center">
            <span class="text-sm font-medium text-color-2 group-hover:text-gray-800 mr-2">{{ __('shop.next') }}</span>
            <svg class="h-5 w-5 text-color-2 group-hover:text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L13.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l1.158-1.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
        </button>
    @else
        <div class="px-4 py-3 flex items-center">
            {{-- Invisible placeholder with same dimensions as Next button --}}
            <span class="text-sm font-medium invisible mr-2">{{ __('shop.next') }}</span>
            <svg class="h-5 w-5 invisible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L13.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l1.158-1.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
        </div>
    @endif
</div>

{{-- Mobile Compact Version --}}
<div class="flex items-center justify-between mt-6 sm:hidden">
    <button 
        wire:click="previousPage" 
        @if(!$this->hasPreviousPage) disabled @endif
        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md 
               {{ $this->hasPreviousPage ? 'bg-white text-color-2 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
        @if($this->hasPreviousPage)
            <svg class="h-4 w-4 text-color-2 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M17 10.75a.75.75 0 01-.75.75H6.5l1.2 2a.75.75 0 11-1.4.8L3.5 11.2a.75.75 0 010-.9l2.8-3.35a.75.75 0 111.4.8L6.5 10H16.25A.75.75 0 0117 10.75z" clip-rule="evenodd" />
            </svg>
        @endif
        {{ __('shop.previous') }}
    </button>
    
    <span class="text-sm text-gray-700">
        {{ __('Page') }} {{ $currentPage }} {{ __('of') }} {{ $this->totalPages }}
    </span>
    
    <button 
        wire:click="nextPage" 
        @if(!$this->hasNextPage) disabled @endif
        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md 
               {{ $this->hasNextPage ? 'bg-white text-color-2 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
        {{ __('shop.next') }}
        @if($this->hasNextPage)
            <svg class="h-4 w-4 text-color-2 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L13.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l1.158-1.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
            </svg>
        @endif
    </button>
</div>

    {{-- Results Summary --}}
    @if($totalItems > 0)
        <div class="text-center mt-4 text-sm text-gray-600">
            {{ __('Showing') }} 
            <span class="font-medium">{{ (($currentPage - 1) * $perPage) + 1 }}</span>
            {{ __('to') }} 
            <span class="font-medium">{{ min($currentPage * $perPage, $totalItems) }}</span>
            {{ __('of') }} 
            <span class="font-medium">{{ $totalItems }}</span>
            {{ __('results') }}
        </div>
    @endif
    @endif
</div>