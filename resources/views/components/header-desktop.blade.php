<div class="hidden md:block">
    <div class="h-9 bg-[#D29289] pl-32 overflow-hidden hidden">
        <div class="w-full h-full flex">
            <div class="animate-marquee whitespace-nowrap my-auto">
                <p class="text-theme-color-2 font-robotoCondensed font-regular">
                    {{ trans('components/header.banner-text') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ trans('components/header.banner-text') }}
                </p>
            </div>
        </div>
    </div>
    <div class="relative flex w-full h-16">

        <!-- Left Column -->
        <div class="bg-background-color-4 w-full flex items-center py-4 pl-16">
            @livewire('shared.search-bar')
        </div>
        <!-- Right Column -->
        <div class="flex w-full bg-background-color-4">
            <!-- Left Column -->
            <div class="w-1/2 ">
                
            </div>

            <!-- Right Column with Two Inner Columns -->
            <div class="w-1/2 bg-background-color-4 flex items-center">
                
                <div class="ml-10 bg-background-color-4 flex items-center justify-center">
                    <!-- Botón Vende tu bolso -->
                    <div class="flex items-center">
                        <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}"
                            class="mt-2 px-5 h-8 py-[7px] font-robotoCondensed bg-background-color-2 text-white rounded-full text-xs font-regular hover:bg-background-color-3 transition whitespace-nowrap">
                            {{ trans('components/header.button-sell-your-bag') }}
                        </a>
                    </div>
                    <!-- Espaciador responsive -->
                    <div class="w-4 md:w-6 lg:w-8"></div>
                    <div class="flex-1 flex justify-center px-1">
                        <a href="{{ route(app()->getLocale() === 'es' ? 'login.show.en-es' : 'login.show.en-es', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ request()->routeIs('login.show.en-es') || request()->routeIs('my-account.show.en') || request()->routeIs('my-account.show.es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header.svg') }}" 
                                class="w-7 h-5 cursor-pointer mt-[3px]"
                                onmouseover="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header_hover.svg') }}'"
                                onmouseout="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header.svg') }}'">
                        </a>
                    </div>
                    <div class="flex-1 flex justify-center px-1">
                        @livewire('cart.icon')
                    </div>
                    <div class="flex-1 flex justify-center mr-3 pt-1">
                        @livewire('shared/language-selector')
                    </div>
                    
                    <div class="flex-1 flex justify-center px-1">
                        <a target="_blank" href="https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr">
                            <img src="{{ asset('images/icons/RRSS_insta_b_5.svg') }}" 
                                class="w-7 h-5 cursor-pointer mt-[3px]"
                                onmouseover="this.style.filter='brightness(0) saturate(100%) invert(23%) sepia(98%) saturate(2074%) hue-rotate(353deg) brightness(83%) contrast(94%)'"
                                onmouseout="this.style.filter='none'">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centered Image -->
        <div class="absolute left-1/2 w-1/8 transform -translate-x-1/2">
            <a href="{{ url(app()->getLocale() === 'es' ? '/es' : '/en') }}">  
                <img src="{{ asset('images/logo/bags_and_tea_logo_new.svg') }}" class="mx-16 my-4 h-9 cursor-pointer"> 
            </a>
        </div>

    </div>
    <div class="w-full border-t-2 border-t-theme-color-1">

    </div>
    <div class="h-16 bg-background-color-4 flex items-center pt-2">
        <div class="flex w-full max-w-screen-2xl mx-auto px-4 items-center">
            <!-- Columna izquierda (espacio igual al botón) -->
            <div class="w-[160px]"></div>
            <!-- Menú centrado -->
            <nav class="flex flex-row items-center space-x-8 mx-auto">
                <a href="{{ route(app()->getLocale() === 'es' ? 'repair-your-bag.show.es' : 'repair-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('repair-your-bag.show.es') || request()->routeIs('repair-your-bag.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14 flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2 px-4 whitespace-nowrap">{{ trans('components/header.menu-option-1') }}</a>
                <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('we-buy-your-bag.show.es') || request()->routeIs('we-buy-your-bag.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14 flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2 px-4 whitespace-nowrap">{{ trans('components/header.menu-option-2') }}</a>

                @php
                    // Get the "Bags" parent category and its children
                    $bagsParentCategory = \DB::table('categories')
                        ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.en")) = ?', ['Bags'])
                        ->first();

                    $bagCategories = [];
                    if ($bagsParentCategory) {
                        $categories = \DB::table('categories')
                            ->where('parent_id', $bagsParentCategory->id)
                            ->get();

                        // Sort alphabetically by translated name
                        $bagCategories = $categories->sortBy(function($category) {
                            $name = is_string($category->name) ? json_decode($category->name, true) : $category->name;
                            return $name[app()->getLocale()] ?? $name['en'] ?? '';
                        })->values();
                    }
                @endphp

                <!-- Shop menu with dropdown -->
                <div class="relative group h-14">
                    <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}"
                       class="{{ request()->routeIs('shop.show.es') || request()->routeIs('shop.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14 flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2 px-4 whitespace-nowrap">
                        {{ trans('components/header.menu-option-3') }}
                    </a>

                    <!-- Dropdown menu -->
                    @if(count($bagCategories) > 0)
                        <div class="absolute left-0 top-full hidden group-hover:block bg-background-color-4 shadow-lg z-50 pt-1 border-t border-l border-r border-gray-200">
                            @foreach($bagCategories as $category)
                                @php
                                    $categoryName = is_string($category->name) ? json_decode($category->name, true) : $category->name;
                                    $categorySlug = is_string($category->slug) ? json_decode($category->slug, true) : $category->slug;
                                    $translatedName = $categoryName[app()->getLocale()] ?? $categoryName['en'] ?? '';
                                    $translatedSlug = $categorySlug[app()->getLocale()] ?? $categorySlug['en'] ?? '';
                                @endphp
                                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale(), 'slug' => $translatedSlug]) }}"
                                   class="block px-4 py-2 text-color-2 font-robotoCondensed text-sm font-medium hover:text-color-3 whitespace-nowrap border-b border-gray-200">
                                    {{ $translatedName }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('about-us.show.es') || request()->routeIs('about-us.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14 flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2 px-4 whitespace-nowrap">{{ trans('components/header.menu-option-4') }}</a>
                <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('blog.show.en-es') || request()->routeIs('article.show.es') || request()->routeIs('article.show.es') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14 flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2 px-4 whitespace-nowrap">{{ trans('components/header.menu-option-5') }}</a>
                <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('contact.send.es') || request()->routeIs('contact.send.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14 flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2 px-4 whitespace-nowrap">{{ trans('components/header.menu-option-6') }}</a>
            </nav>
            <!-- Espacio vacío -->
            <div class="w-[160px]"></div>
        </div>
    </div>
    </div>
</div>


