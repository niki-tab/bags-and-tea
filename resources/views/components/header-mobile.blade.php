<div class="md:hidden bg-background-color-4 text-white h-[80px] fixed top-0 w-full">
    <div class="h-8 w-full bg-background-color-1 flex items-center justify-center hidden"> 
        <div class="animate-marquee inline-block">
            <p class="text-theme-color-2 font-robotoCondensed font-regular text-sm">{{ trans('components/header.banner-text') }}</p>
        </div>
    </div>    
    <div class="flex justify-between items-center"> 
        <!-- Logo (on the left) -->
        <div class="text-left ml-8 mt-4">
            <a href="/{{app()->getLocale()}}" class="">
                <img src="{{ asset('images/logo/bags_and_tea_logo_mobile.svg') }}" alt="logo" width="60" height="60">
            </a>
        </div>
        <!-- Buttons (stacked vertically) -->
        <div class="flex flex-col gap-2 flex-grow pr-4 pt-1">
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" 
            class="mt-4 mr-4 px-5 py-1.5 font-robotoCondensed bg-background-color-2 text-white rounded-full text-sm font-regular hover:bg-background-color-3 transition ml-auto">
                {{ trans('components/header.button-sell-your-bag') }}
            </a>
        </div>
        <div class="flex items-center gap-3 mt-5 mr-6">
            <button id="hamburgerMenu" class="focus:outline-none">
                <img src="{{ asset('images/icons/mobile_burguer_menu_icon.svg') }}" alt="hamburger menu" width="25" id="menuIcon"
                data-menu-open="{{ asset('images/icons/icon_hamburguer_menu_close.svg') }}"
                data-menu-closed="{{ asset('images/icons/mobile_burguer_menu_icon.svg') }}">
            </button>
        </div>
    </div>
        
    <div id="hamburgerMenuOptions" class="hidden bg-background-color-4 w-full pt-4 mt-4 max-h-[calc(100vh-80px)] overflow-y-auto">
        @livewire('shared.search-bar-mobile')
        <div class="flex items-center gap-3 justify-center">
            <a target="_blank" href="https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr">
                <img src="{{asset('images/icons/mobile_header_instagram.svg') }}" 
                    class="mt-[0.5px] w-6 h-7 cursor-pointer"
                    onmouseover="this.src='{{asset('images/icons/mobile_header_instagram_hover.svg') }}'"
                    onmouseout="this.src='{{asset('images/icons/mobile_header_instagram.svg') }}'">
            </a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'login.show.en-es' : 'login.show.en-es', ['locale' => app()->getLocale()]) }}" class="ml-6">
                <img src="{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_user_avatar_clicked.svg') : asset('images/icons/mobile_user_avatar.svg') }}" 
                    class="w-6 h-6 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('login.show.en-es') || request()->routeIs('my-account.show.en') || request()->routeIs('my-account.show.es') ? asset('images/icons/mobile_user_avatar_clicked.svg') : asset('images/icons/mobile_user_avatar_hover.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('login.show.en-es') || request()->routeIs('my-account.show.en') || request()->routeIs('my-account.show.es') ? asset('images/icons/mobile_user_avatar_clicked.svg') : asset('images/icons/mobile_user_avatar.svg') }}'">
            </a>
            <div class="mb-1">
                @livewire('cart.icon')
            </div>
            <div class="ml-[-8px] mt-[2px]">
                @livewire('shared/language-selector')
            </div>
        </div>
        @php
            // Get the Bags parent category by slug (bags or bolsos)
            $bagsParentCategory = \DB::table('categories')
                ->where(function($query) {
                    $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['bags'])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.es")) = ?', ['bolsos']);
                })
                ->whereNull('parent_id')
                ->first();

            $bagCategories = [];
            $bagsParentSlug = null;
            $allBagSlugs = [];
            if ($bagsParentCategory) {
                $bagsSlugData = is_string($bagsParentCategory->slug) ? json_decode($bagsParentCategory->slug, true) : $bagsParentCategory->slug;
                $bagsParentSlug = $bagsSlugData[app()->getLocale()] ?? $bagsSlugData['en'] ?? '';

                // Collect all bag slugs for active state detection
                $allBagSlugs[] = $bagsSlugData['en'] ?? '';
                $allBagSlugs[] = $bagsSlugData['es'] ?? '';

                $categories = \DB::table('categories')
                    ->where('parent_id', $bagsParentCategory->id)
                    ->get();

                // Sort alphabetically by translated name
                $bagCategories = $categories->sortBy(function($category) {
                    $name = is_string($category->name) ? json_decode($category->name, true) : $category->name;
                    return $name[app()->getLocale()] ?? $name['en'] ?? '';
                })->values();

                // Also collect all children slugs for active state
                foreach ($categories as $child) {
                    $childSlug = is_string($child->slug) ? json_decode($child->slug, true) : $child->slug;
                    $allBagSlugs[] = $childSlug['en'] ?? '';
                    $allBagSlugs[] = $childSlug['es'] ?? '';
                }
            }
            $allBagSlugs = array_filter(array_unique($allBagSlugs));

            // Get the Wallets parent category by slug (wallets or carteras)
            $walletsParentCategory = \DB::table('categories')
                ->where(function($query) {
                    $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['wallets'])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.es")) = ?', ['carteras']);
                })
                ->whereNull('parent_id')
                ->first();

            $walletCategories = [];
            $walletsParentSlug = null;
            $allWalletSlugs = [];
            if ($walletsParentCategory) {
                $walletsSlugData = is_string($walletsParentCategory->slug) ? json_decode($walletsParentCategory->slug, true) : $walletsParentCategory->slug;
                $walletsParentSlug = $walletsSlugData[app()->getLocale()] ?? $walletsSlugData['en'] ?? '';

                // Collect all wallet slugs for active state detection
                $allWalletSlugs[] = $walletsSlugData['en'] ?? '';
                $allWalletSlugs[] = $walletsSlugData['es'] ?? '';

                $categories = \DB::table('categories')
                    ->where('parent_id', $walletsParentCategory->id)
                    ->get();

                // Sort alphabetically by translated name
                $walletCategories = $categories->sortBy(function($category) {
                    $name = is_string($category->name) ? json_decode($category->name, true) : $category->name;
                    return $name[app()->getLocale()] ?? $name['en'] ?? '';
                })->values();

                // Also collect all children slugs for active state
                foreach ($categories as $child) {
                    $childSlug = is_string($child->slug) ? json_decode($child->slug, true) : $child->slug;
                    $allWalletSlugs[] = $childSlug['en'] ?? '';
                    $allWalletSlugs[] = $childSlug['es'] ?? '';
                }
            }
            $allWalletSlugs = array_filter(array_unique($allWalletSlugs));

            // Determine active states based on current URL slug
            $currentSlug = request()->route('slug');
            $isOnShopPage = request()->routeIs('shop.show.es') || request()->routeIs('shop.show.en');
            $isWalletsActive = $isOnShopPage && in_array($currentSlug, $allWalletSlugs);
            // Bags is active when on shop page with bag slug OR when on shop page without any slug (main shop = bags)
            $isBagsActive = $isOnShopPage && (in_array($currentSlug, $allBagSlugs) || empty($currentSlug));

            // Get all "Bag Models" parent categories and their children for Popular Models menu
            $bagModelsParents = \DB::table('categories')
                ->where(function($query) {
                    $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['louis-vuitton-bag-models'])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['gucci-bag-models'])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['fendi-bag-models'])
                          ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', ['christian-dior-bag-models']);
                })
                ->where('is_active', true)
                ->pluck('id');

            $popularModels = [];
            $allPopularModelSlugs = [];
            if ($bagModelsParents->count() > 0) {
                $models = \DB::table('categories')
                    ->whereIn('parent_id', $bagModelsParents)
                    ->where('is_active', true)
                    ->get();

                // Sort alphabetically by translated name
                $popularModels = $models->sortBy(function($model) {
                    $name = is_string($model->name) ? json_decode($model->name, true) : $model->name;
                    return $name[app()->getLocale()] ?? $name['en'] ?? '';
                })->values();

                foreach ($popularModels as $model) {
                    $modelSlug = is_string($model->slug) ? json_decode($model->slug, true) : $model->slug;
                    $allPopularModelSlugs[] = $modelSlug['en'] ?? '';
                    $allPopularModelSlugs[] = $modelSlug['es'] ?? '';
                }
            }
            $allPopularModelSlugs = array_filter(array_unique($allPopularModelSlugs));
            $allPopularModelSlugs[] = 'popular-models';
            $allPopularModelSlugs[] = 'modelos-populares';
            $isPopularModelsActive = $isOnShopPage && in_array($currentSlug, $allPopularModelSlugs);
        @endphp

        <div class="mt-4">
            <a href="{{ route(app()->getLocale() === 'es' ? 'repair-your-bag.show.es' : 'repair-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('repair-your-bag.show.es') || request()->routeIs('repair-your-bag.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-t border-[#E6D4CB]">{{ trans('components/header.menu-option-1') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('we-buy-your-bag.show.es') || request()->routeIs('we-buy-your-bag.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-2') }}</a>

            <!-- Our Bags menu with expandable dropdown -->
            <div class="border-b border-[#E6D4CB]">
                <div class="flex items-center justify-center py-4 relative">
                    <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" class="{{ $isBagsActive ? 'font-bold text-theme-color-2' : 'text-color-2' }} text-2xl hover:underline font-robotoCondensed flex-grow text-center">{{ trans('components/header.menu-option-4') }}</a>
                    @if(count($bagCategories) > 0)
                        <button id="bagsDropdownToggle" class="absolute right-4 focus:outline-none p-2" aria-label="Toggle bag categories">
                            <svg id="bagsDropdownIcon" class="w-6 h-6 text-color-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    @endif
                </div>

                @if(count($bagCategories) > 0)
                    <div id="bagsDropdownMenu" class="hidden overflow-hidden">
                        <div class="pb-2">
                            @foreach($bagCategories as $category)
                                @php
                                    $categoryName = is_string($category->name) ? json_decode($category->name, true) : $category->name;
                                    $categorySlug = is_string($category->slug) ? json_decode($category->slug, true) : $category->slug;
                                    $translatedName = $categoryName[app()->getLocale()] ?? $categoryName['en'] ?? '';
                                    $translatedSlug = $categorySlug[app()->getLocale()] ?? $categorySlug['en'] ?? '';
                                @endphp
                                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale(), 'slug' => $translatedSlug]) }}"
                                   class="block text-color-2 font-robotoCondensed text-lg hover:text-color-3 py-2 text-center">
                                    {{ $translatedName }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Popular Models menu with expandable dropdown -->
            <div class="border-b border-[#E6D4CB]">
                <div class="flex items-center justify-center py-4 relative">
                    <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale(), 'slug' => app()->getLocale() === 'es' ? 'modelos-populares' : 'popular-models']) }}" class="{{ $isPopularModelsActive ? 'font-bold text-theme-color-2' : 'text-color-2' }} text-2xl hover:underline font-robotoCondensed flex-grow text-center">{{ trans('components/header.menu-option-9') }}</a>
                    @if(count($popularModels) > 0)
                        <button id="popularModelsDropdownToggle" class="absolute right-4 focus:outline-none p-2" aria-label="Toggle popular models">
                            <svg id="popularModelsDropdownIcon" class="w-6 h-6 text-color-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    @endif
                </div>

                @if(count($popularModels) > 0)
                    <div id="popularModelsDropdownMenu" class="hidden overflow-hidden">
                        <div class="pb-2">
                            @foreach($popularModels as $model)
                                @php
                                    $modelName = is_string($model->name) ? json_decode($model->name, true) : $model->name;
                                    $modelSlug = is_string($model->slug) ? json_decode($model->slug, true) : $model->slug;
                                    $translatedModelName = $modelName[app()->getLocale()] ?? $modelName['en'] ?? '';
                                    $translatedModelSlug = $modelSlug[app()->getLocale()] ?? $modelSlug['en'] ?? '';
                                @endphp
                                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale(), 'slug' => $translatedModelSlug]) }}"
                                   class="block text-color-2 font-robotoCondensed text-lg hover:text-color-3 py-2 text-center">
                                    {{ $translatedModelName }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Our Wallets menu with expandable dropdown -->
            <div class="border-b border-[#E6D4CB]">
                <div class="flex items-center justify-center py-4 relative">
                    <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale(), 'slug' => $walletsParentSlug]) }}" class="{{ $isWalletsActive ? 'font-bold text-theme-color-2' : 'text-color-2' }} text-2xl hover:underline font-robotoCondensed flex-grow text-center">{{ trans('components/header.menu-option-8') }}</a>
                    @if(count($walletCategories) > 0)
                        <button id="walletsDropdownToggle" class="absolute right-4 focus:outline-none p-2" aria-label="Toggle wallet categories">
                            <svg id="walletsDropdownIcon" class="w-6 h-6 text-color-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    @endif
                </div>

                @if(count($walletCategories) > 0)
                    <div id="walletsDropdownMenu" class="hidden overflow-hidden">
                        <div class="pb-2">
                            @foreach($walletCategories as $category)
                                @php
                                    $categoryName = is_string($category->name) ? json_decode($category->name, true) : $category->name;
                                    $categorySlug = is_string($category->slug) ? json_decode($category->slug, true) : $category->slug;
                                    $translatedName = $categoryName[app()->getLocale()] ?? $categoryName['en'] ?? '';
                                    $translatedSlug = $categorySlug[app()->getLocale()] ?? $categorySlug['en'] ?? '';
                                @endphp
                                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale(), 'slug' => $translatedSlug]) }}"
                                   class="block text-color-2 font-robotoCondensed text-lg hover:text-color-3 py-2 text-center">
                                    {{ $translatedName }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('about-us.show.es') || request()->routeIs('about-us.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-5') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('blog.show.en-es') || request()->routeIs('article.show.es') || request()->routeIs('article.show.es') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-6') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('contact.send.es') || request()->routeIs('contact.send.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-7') }}</a>
        </div>
    </div>
</div>
<script>
    // JavaScript to toggle the menu
    document.getElementById('hamburgerMenu').addEventListener('click', function() {
        const menu = document.getElementById('hamburgerMenuOptions');
        const icon = document.getElementById('menuIcon');

        menu.classList.toggle('hidden');

        // Toggle icon
        if (menu.classList.contains('hidden')) {
            icon.src = icon.dataset.menuClosed;
        } else {
            icon.src = icon.dataset.menuOpen;
        }
    });

    // JavaScript to toggle the bags dropdown
    const bagsDropdownToggle = document.getElementById('bagsDropdownToggle');
    if (bagsDropdownToggle) {
        bagsDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const menu = document.getElementById('bagsDropdownMenu');
            const icon = document.getElementById('bagsDropdownIcon');

            menu.classList.toggle('hidden');

            // Rotate the arrow icon
            if (menu.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });
    }

    // JavaScript to toggle the wallets dropdown
    const walletsDropdownToggle = document.getElementById('walletsDropdownToggle');
    if (walletsDropdownToggle) {
        walletsDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const menu = document.getElementById('walletsDropdownMenu');
            const icon = document.getElementById('walletsDropdownIcon');

            menu.classList.toggle('hidden');

            // Rotate the arrow icon
            if (menu.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });
    }

    // JavaScript to toggle the popular models dropdown
    const popularModelsDropdownToggle = document.getElementById('popularModelsDropdownToggle');
    if (popularModelsDropdownToggle) {
        popularModelsDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const menu = document.getElementById('popularModelsDropdownMenu');
            const icon = document.getElementById('popularModelsDropdownIcon');

            menu.classList.toggle('hidden');

            // Rotate the arrow icon
            if (menu.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        });
    }

</script>