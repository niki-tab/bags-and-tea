<div class="md:hidden bg-background-color-4 text-white h-28 fixed top-0 w-full">
    <div class="flex justify-between items-center"> 
        <!-- Logo (on the left) -->
        <div class="text-left mt-3 ml-4 p-4">
            <a href="/{{app()->getLocale()}}" class="">
                <img src="{{ asset('images/logo/bags_and_tea_logo.svg') }}" alt="logo" width="90" height="90">
            </a>
        </div>
        <!-- Buttons (stacked vertically) -->
        <div class="flex flex-col items-center gap-2">
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="px-4 h-9 py-2 font-robotoCondensed bg-background-color-2 text-white rounded-full text-sm font-regular hover:bg-background-color-3 transition">
                {{ trans('components/header.button-sell-your-bag') }}
            </a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="px-4 h-9 py-2 font-robotoCondensed bg-background-color-2 text-white rounded-full text-sm font-regular hover:bg-background-color-3 transition">
                {{ trans('components/header.button-sell-your-bag') }}
            </a>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route(app()->getLocale() === 'es' ? 'login.show.en-es' : 'login.show.en-es', ['locale' => app()->getLocale()]) }}">
                <img src="{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header.svg') }}" 
                    class="w-8 h-8 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header_hover.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header.svg') }}'">
            </a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'cart.edit.es' : 'cart.edit.en', ['locale' => app()->getLocale()]) }}">
                <img src="{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header.svg') }}" 
                    class="w-8 h-8 cursor-pointer"
                    onmouseover="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header_hover.svg') }}'"
                    onmouseout="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header.svg') }}'">
            </a>
        </div>
        <!-- Hamburger Menu (on the right) -->
        <div class="text-right mr-4 p-4">
            <!-- Toggle Menu on Click -->
            <button id="hamburgerMenu" class="focus:outline-none">
                <img src="{{ asset('images/icons/hamburguer_menu_2.png') }}" alt="hamburger menu" width="45" height="78">
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