<div class="md:hidden bg-background-color-4 text-white h-[112px] fixed top-0 w-full mb-20">
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
        
    <div id="hamburgerMenuOptions" class="hidden bg-background-color-4 w-full pt-4 mt-4">
        <div class="relative w-5/6 mx-auto mb-4">
            <img src="{{ asset('images/icons/vector_search_icon.svg') }}"  
                    class="absolute left-5 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" 
                    alt="Search Icon">
            <input type="text" class="h-12 pl-12 placeholder:font-robotoCondensed placeholder:text-color-2  placeholder:font-light w-full p-2 bg-white text-color-2 rounded-full border border-gray-300 font-robotoCondensed" placeholder="{{ trans('components/header.placeholder-input-search-product') }}">
        </div>
        <div class="flex items-center gap-3 justify-center">
            <a target="_blank" href="https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr">
                <img src="{{asset('images/icons/mobile_header_instagram.svg') }}" 
                    class="mt-[0.5px] w-6 h-7 cursor-pointer"
                    onmouseover="this.src='{{asset('images/icons/mobile_header_instagram_hover.svg') }}'"
                    onmouseout="this.src='{{asset('images/icons/mobile_header_instagram.svg') }}'">
            </a>
            <a target="_blank" href="https://www.vinted.es/member/250362636-bagsandtea">
                <img src="{{asset('images/icons/mobile_header_vinted.svg') }}" 
                    class="w-6 h-6 cursor-pointer"
                    onmouseover="this.src='{{asset('images/icons/mobile_header_vinted_hover.svg') }}'"
                    onmouseout="this.src='{{asset('images/icons/mobile_header_vinted.svg') }}'">
            </a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'login.show.en-es' : 'login.show.en-es', ['locale' => app()->getLocale()]) }}" class="ml-6">
                <img src="{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_user_avatar_clicked.svg') : asset('images/icons/mobile_user_avatar.svg') }}" 
                    class="w-6 h-6 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_user_avatar_clicked.svg') : asset('images/icons/mobile_user_avatar_hover.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/mobile_user_avatar_clicked.svg') : asset('images/icons/mobile_user_avatar.svg') }}'">
            </a>
            <a class="mb-1" href="{{ route(app()->getLocale() === 'es' ? 'cart.edit.es' : 'cart.edit.en', ['locale' => app()->getLocale()]) }}">
                <img src="{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/mobile_cart_icon_clicked_2.svg') : asset('images/icons/mobile_cart_icon.svg') }}" 
                    class="w-8 h-8 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/mobile_cart_icon_clicked_2.svg') : asset('images/icons/mobile_cart_icon_clicked.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/mobile_cart_icon_clicked_2.svg') : asset('images/icons/mobile_cart_icon.svg') }}'">
            </a>
            <div class="ml-[-8px] mt-[2px]">
                @livewire('shared/language-selector')
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ route(app()->getLocale() === 'es' ? 'certify-your-bag.show.es' : 'certify-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('certify-your-bag.show.es') || request()->routeIs('certify-your-bag.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-t border-[#E6D4CB]">{{ trans('components/header.menu-option-1') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('we-buy-your-bag.show.es') || request()->routeIs('we-buy-your-bag.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-2') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('shop.show.es') || request()->routeIs('shop.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-3') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('about-us.show.es') || request()->routeIs('about-us.show.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-4') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('blog.show.en-es') || request()->routeIs('article.show.es') || request()->routeIs('article.show.es') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-5') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('contact.send.es') || request()->routeIs('contact.send.en') ? 'font-bold text-theme-color-2' : 'text-color-2' }} block text-2xl hover:underline py-4 font-robotoCondensed text-center border-b border-[#E6D4CB]">{{ trans('components/header.menu-option-6') }}</a>
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

</script>