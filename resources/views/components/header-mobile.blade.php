<div class="md:hidden bg-background-color-4 text-white h-[110px] fixed top-0 w-full mb-20">
    <div class="h-8 w-full bg-background-color-1 flex items-center justify-center"> 
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
        <div class="flex flex-col items-center gap-2">
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="mt-4 mr-4 px-5 py-1.5 font-robotoCondensed bg-background-color-2 text-white rounded-full text-sm font-regular hover:bg-background-color-3 transition">
                {{ trans('components/header.button-sell-your-bag') }}
            </a>
        </div>
        <div class="flex items-center gap-3 mt-5 mr-6">
            <a href="{{ route(app()->getLocale() === 'es' ? 'login.show.en-es' : 'login.show.en-es', ['locale' => app()->getLocale()]) }}">
                <img src="{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_search_icon_clicked.svg') : asset('images/icons/mobile_search_icon.svg') }}" 
                    class="w-6 h-6 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_search_icon_clicked.svg') : asset('images/icons/mobile_search_icon_clicked.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_search_icon_clicked.svg') : asset('images/icons/mobile_search_icon.svg') }}'">
            </a>
            <a class="mb-1" href="{{ route(app()->getLocale() === 'es' ? 'cart.edit.es' : 'cart.edit.en', ['locale' => app()->getLocale()]) }}">
                <img src="{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/mobile_cart_icon_clicked.svg') : asset('images/icons/mobile_cart_icon.svg') }}" 
                    class="w-8 h-8 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/mobile_cart_icon_clicked.svg') : asset('images/icons/mobile_cart_icon_clicked.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/mobile_cart_icon_clicked.svg') : asset('images/icons/mobile_cart_icon.svg') }}'">
            </a>
            <button id="hamburgerMenu" class="focus:outline-none">
                <img src="{{ asset('images/icons/mobile_burguer_menu_icon.svg') }}" alt="hamburger menu" width="25"
                onmouseover="this.src='{{ asset('images/icons/mobile_burguer_menu_icon_clicked.svg') }}'"
                onmouseout="this.src='{{ asset('images/icons/mobile_burguer_menu_icon.svg') }}'">
            </button>
        </div>
    </div>
        
    <div id="hamburgerMenuOptions" class="hidden bg-background-color-4 w-full py-4 mt-4">
        <div class="relative w-4/5 mx-auto mb-4">
            <img src="{{ asset('images/icons/vector_search_icon.svg') }}" 
                    class="absolute left-5 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" 
                    alt="Search Icon">
            <input type="text" class="pl-12 placeholder:font-robotoCondensed placeholder:text-color-2  placeholder:font-light w-full p-2 bg-white text-color-2 rounded-full border border-gray-300 font-robotoCondensed" placeholder="{{ trans('components/header.placeholder-input-search-product') }}">
        </div>
        <div class="space-y-4 mt-8">
            <a href="{{ route(app()->getLocale() === 'es' ? 'certify-your-bag.show.es' : 'certify-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('certify-your-bag.show.es') || request()->routeIs('certify-your-bag.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-lg hover:underline pb-2 font-robotoCondensed ml-12">{{ trans('components/header.menu-option-1') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('we-buy-your-bag.show.es') || request()->routeIs('we-buy-your-bag.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-lg hover:underline pb-2 font-robotoCondensed ml-12">{{ trans('components/header.menu-option-2') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'our-bags.show.es' : 'our-bags.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('our-bags.show.es') || request()->routeIs('our-bags.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-lg hover:underline pb-2 font-robotoCondensed ml-12">{{ trans('components/header.menu-option-3') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('about-us.show.es') || request()->routeIs('about-us.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-lg hover:underline pb-2 font-robotoCondensed ml-12">{{ trans('components/header.menu-option-4') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('blog.show.en-es') || request()->routeIs('article.show.es') || request()->routeIs('article.show.es') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-lg hover:underline pb-2 font-robotoCondensed ml-12">{{ trans('components/header.menu-option-5') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('contact.send.es') || request()->routeIs('contact.send.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-lg hover:underline pb-2 font-robotoCondensed ml-12">{{ trans('components/header.menu-option-6') }}</a>
        </div>
    </div>
</div>
<script>
    // JavaScript to toggle the menu
    document.getElementById('hamburgerMenu').addEventListener('click', function() {
        var menu = document.getElementById('mobileMenu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    });
        
    
    document.getElementById('hamburgerMenu').addEventListener('click', function() {
        const menu = document.getElementById('hamburgerMenuOptions');
        menu.classList.toggle('hidden');
    });

</script>