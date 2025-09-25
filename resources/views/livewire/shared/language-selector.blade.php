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
                <a href="{{ route($routeEnglish, $paramsEnglish) }}"
                   class="text-color-2 block px-4 py-2 text-sm hover:bg-gray-100">EN</a>
                <a href="{{ route($routeSpanish, $paramsSpanish) }}"
                   class="text-color-2 block px-4 py-2 text-sm hover:bg-gray-100">ES</a>
            </div>
        </div>
    </button>
</div>