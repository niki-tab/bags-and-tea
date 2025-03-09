<div class="hidden md:block">
    <div class="h-9 bg-background-color-1 pl-32 flex">
        <div class="flex-1 flex gap-4 pt-2">
            <img src="{{ asset('images/icons/RRSS_insta_b_5.svg') }}" class="w-5 h-5 cursor-pointer pt-[1px]">    
            <img src="{{ asset('images/icons/RRSS_facebook_b.svg') }}" class="w-5 h-5 cursor-pointer">
        </div>
        <div class="flex-1 flex items-center justify-center">
            <p class="text-theme-color-2 font-robotoCondensed font-bold">¿1ª compra? -15% con el código WELCOME15</p>
        </div>
        
    </div>
    <div class="relative flex w-full h-16">

        <!-- Left Column -->
        <div class="bg-background-color-4 w-full flex items-center py-4 pl-16">
            <div class="relative w-3/5">
                <img src="{{ asset('images/icons/vector_search_icon.svg') }}" 
                class="absolute left-5 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" 
                alt="Search Icon">

                <input type="text" 
                    class="text-sm h-10 placeholder:font-robotoCondensed placeholder:text-color-2  placeholder:font-light font-robotoCondensed w-full p-3 pl-12 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    placeholder="{{ trans('components/header.placeholder-input-search-product') }}"
                >
            </div>
        </div>
        <!-- Right Column -->
        <div class="flex w-full bg-background-color-4">
            <!-- Left Column -->
            <div class="w-1/2 ">
                
            </div>

            <!-- Right Column with Two Inner Columns -->
            <div class="w-1/2 bg-background-color-4 flex items-center">
                <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="px-8 h-8 py-1.5 font-robotoCondensed bg-background-color-2 text-white rounded-full text-sm font-regular hover:bg-background-color-3 transition">
                    {{ trans('components/header.button-sell-your-bag') }}
                </a>
                <div class="ml-10 bg-background-color-4 flex items-center justify-center">
                    <div class="flex-1 flex justify-center px-1">
                        <a href="{{ route(app()->getLocale() === 'es' ? 'login.show.en-es' : 'login.show.en-es', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header.svg') }}" 
                                class="w-8 h-7 cursor-pointer"
                                onmouseover="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header_hover.svg') }}'"
                                onmouseout="this.src='{{ request()->routeIs('login.show.en-es') ? asset('images/icons/icon_user_avatar_header_clicked.svg') : asset('images/icons/icon_user_avatar_header.svg') }}'">
                        </a>
                    </div>
                    <div class="flex-1 flex justify-center px-1">
                        <a href="{{ route(app()->getLocale() === 'es' ? 'cart.edit.es' : 'cart.edit.en', ['locale' => app()->getLocale()]) }}">
                            <img src="{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header.svg') }}" 
                                class="w-8 h-7 cursor-pointer"
                                onmouseover="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header_hover.svg') }}'"
                                onmouseout="this.src='{{ request()->routeIs('cart.edit.es') || request()->routeIs('cart.edit.en') ? asset('images/icons/icon_cart_header_clicked.svg') : asset('images/icons/icon_cart_header.svg') }}'">
                        </a>
                    </div>
                    <div class="flex-1 flex justify-center mr-12 pt-1">
                        @livewire('shared/language-selector')
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Centered Image -->
        <div class="absolute left-1/2 top-[-20%] w-1/8 h-[120%] bg-background-color-4 transform -translate-x-1/2">
            <a href="{{ url(app()->getLocale() === 'es' ? '/es' : '/en') }}">  
                <img src="{{ asset('images/logo/bags_and_tea_logo.svg') }}" class="mx-16 my-2 h-16 cursor-pointer"> 
            </a>
        </div>

    </div>
    <div class="w-full border-t-2 border-t-theme-color-1">

    </div>
    <div class="h-16 bg-background-color-4 flex items-center pt-2">
    <nav class="flex justify-center items-center bg-background-color-4 p-4 pb-0 pt-0 w-full max-w-screen-lg mx-auto space-x-16">
            <a href="{{ route(app()->getLocale() === 'es' ? 'certify-your-bag.show.es' : 'certify-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('certify-your-bag.show.es') || request()->routeIs('certify-your-bag.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14  flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2">{{ trans('components/header.menu-option-1') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('we-buy-your-bag.show.es') || request()->routeIs('we-buy-your-bag.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14  flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2">{{ trans('components/header.menu-option-2') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'our-bags.show.es' : 'our-bags.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('our-bags.show.es') || request()->routeIs('our-bags.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14  flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2">{{ trans('components/header.menu-option-3') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('about-us.show.es') || request()->routeIs('about-us.show.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14  flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2">{{ trans('components/header.menu-option-4') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('blog.show.en-es') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14  flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2">{{ trans('components/header.menu-option-5') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('contact.send.es') || request()->routeIs('contact.send.en') ? 'text-white bg-background-color-3 hover:text-white' : 'text-color-2' }} h-14  flex items-center justify-center text-color-2 font-robotoCondensed text-base font-medium hover:text-color-3 pb-2">{{ trans('components/header.menu-option-6') }}</a>
        </nav>
    </div>


</div>
</div>


