<div>
    <div class="h-12 bg-background-color-1 pl-32 flex">
        <div class="flex-1 flex gap-4 pt-2">
            <img src="{{ asset('images/icons/RRSS_insta_b.svg') }}" class="w-8 h-8 cursor-pointer">    
            <img src="{{ asset('images/icons/RRSS_facebook_b.svg') }}" class="w-8 h-8 cursor-pointer">
        </div>
        <div class="flex-1 flex items-center justify-center">
            <p class="text-theme-color-2 font-robotoCondensed font-bold">¿1ª compra? -15% con el código WELCOME15</p>
        </div>
        
    </div>
    <div class="relative flex w-full h-24">

        <!-- Left Column -->
        <div class="bg-background-color-4 w-full flex items-center py-4 pl-16">
            <div class="relative w-3/5">
                <img src="{{ asset('images/icons/vector_search_icon.svg') }}" 
                class="absolute left-5 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" 
                alt="Search Icon">

                <input type="text" 
                    class="placeholder:font-robotoCondensed placeholder:text-color-2  placeholder:font-light font-robotoCondensed w-full p-3 pl-12 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    placeholder="{{ trans('components/header.placeholder-input-search-product') }}"
                >
            </div>
        </div>
        <!-- Right Column -->
        <div class="flex w-full bg-background-color-4">
            <!-- Left Column -->
            <div class="w-1/2 ">
                Left Content
            </div>

            <!-- Right Column with Two Inner Columns -->
            <div class="w-1/2 bg-background-color-4 flex items-center">
                <a href="#" class="px-8 h-9 py-2 font-robotoCondensed bg-background-color-2 text-white rounded-full text-sm font-regular hover:bg-blue-600 transition">
                    {{ trans('components/header.button-sell-your-bag') }}
                </a>
                <div class="ml-10 w-1/3 bg-background-color-4 flex items-center justify-center">
                    <div class="flex-1 flex justify-center">
                        <img src="{{ asset('images/icons/icon_user_avatar_header.svg') }}" class="w-8 h-8">
                    </div>
                    <div class="flex-1 flex justify-center">
                        <img src="{{ asset('images/icons/icon_cart_header.svg') }}" class="w-8 h-8">
                    </div>
                    <span class="flex-1 flex justify-center text-center">ESP</span>
                </div>
            </div>
        </div>

        <!-- Centered Image -->
        <div class="absolute left-1/2 top-[-20%] w-1/8 h-[120%] bg-background-color-4 transform -translate-x-1/2">
            <a href="{{ url(app()->getLocale() === 'es' ? '/es' : '/en') }}">  
                <img src="{{ asset('images/logo/bags_and_tea_logo.svg') }}" class="mx-16 my-4 w-36 h-22 cursor-pointer"> 
            </a>
        </div>

    </div>
    <div class="w-full border-t-2 border-t-theme-color-1">

    </div>
    <div class="h-24 bg-background-color-4 flex items-center px-40">
        <nav class="flex justify-between items-center bg-background-color-4 p-4 pb-0 w-full">
            <a href="#" class="cursor-pointer flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-color-3">{{ trans('components/header.menu-option-1') }}</a>
            <a href="#" class="cursor-pointer flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-color-3">{{ trans('components/header.menu-option-2') }}</a>
            <a href="#" class="cursor-pointer flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-color-3">{{ trans('components/header.menu-option-3') }}</a>
            <a href="#" class="cursor-pointer flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-color-3">{{ trans('components/header.menu-option-4') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="{{ request()->routeIs('blog.show.en-es') ? 'text-white bg-background-color-3' : 'text-color-2' }} h-20 flex-1 flex items-center justify-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-color-3">{{ trans('components/header.menu-option-5') }}</a>
            <a href="#" class="cursor-pointer flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-color-3">{{ trans('components/header.menu-option-6') }}</a>
        </nav>
    </div>


</div>
</div>


