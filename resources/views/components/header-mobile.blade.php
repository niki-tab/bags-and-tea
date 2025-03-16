<div class="md:hidden bg-background-color-4 text-white h-[100px] fixed top-0 w-full">
    <div class="h-4 w-full bg-background-color-1"> 
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
                <img src="{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/mobile_search_icon.svg') }}" 
                    class="w-6 h-6 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header_hover.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/mobile_search_icon.svg') }}'">
            </a>
            <a class="mb-1" href="{{ route(app()->getLocale() === 'es' ? 'cart.edit.es' : 'cart.edit.en', ['locale' => app()->getLocale()]) }}">
                <img src="{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/mobile_cart_icon.svg') }}" 
                    class="w-8 h-8 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header_hover.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/mobile_cart_icon.svg') }}'">
            </a>
            <button id="hamburgerMenu" class="focus:outline-none">
                <img src="{{ asset('images/icons/mobile_burguer_menu_icon.svg') }}" alt="hamburger menu" width="25">
            </button>
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
</script>