{{-- Cart Icon with Counter --}}
<a href="{{ route(app()->getLocale() === 'es' ? 'cart.show.es' : 'cart.show.en', ['locale' => app()->getLocale()]) }}" 
   class="relative flex items-center justify-center">
    
    {{-- Desktop Cart Icon --}}
    <img src="{{ request()->routeIs('cart.show.es') || request()->routeIs('cart.show.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header.svg') }}" 
         alt="{{ trans('components/cart.cart-icon') }}" 
         class="hidden md:block w-7 h-5 cursor-pointer"
         onmouseover="this.src='{{ request()->routeIs('cart.show.es') || request()->routeIs('cart.show.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header_hover.svg') }}'"
         onmouseout="this.src='{{ request()->routeIs('cart.show.es') || request()->routeIs('cart.show.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header.svg') }}'">

    {{-- Mobile Cart Icon --}}
    <img src="{{ request()->routeIs('cart.show.es') || request()->routeIs('cart.show.en') ? asset('images/icons/mobile_cart_icon_clicked_2.svg') : asset('images/icons/mobile_cart_icon.svg') }}" 
         alt="{{ trans('components/cart.cart-icon') }}" 
         class="md:hidden w-8 h-8 cursor-pointer"
         onmouseover="this.src='{{ request()->routeIs('cart.show.es') || request()->routeIs('cart.show.en') ? asset('images/icons/mobile_cart_icon_clicked_2.svg') : asset('images/icons/mobile_cart_icon_clicked.svg') }}'"
         onmouseout="this.src='{{ request()->routeIs('cart.show.es') || request()->routeIs('cart.show.en') ? asset('images/icons/mobile_cart_icon_clicked_2.svg') : asset('images/icons/mobile_cart_icon.svg') }}'">
    
    @if($totalItems > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center min-w-[20px]">
            {{ $totalItems > 99 ? '99+' : $totalItems }}
        </span>
    @endif
</a>