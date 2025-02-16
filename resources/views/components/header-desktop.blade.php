<div>
    <div class="h-12 bg-background-color-1 pl-32 flex">
        <div class="flex-1 flex gap-4 pt-2">
            <img src="{{ asset('images/icons/RRSS_insta_b.svg') }}" class="w-8 h-8">    
            <img src="{{ asset('images/icons/RRSS_facebook_b.svg') }}" class="w-8 h-8">
        </div>
        <div class="flex-1 flex items-center justify-center">
            <p class="text-theme-color-2 font-robotoCondensed font-bold">¿1ª compra? -15% con el código WELCOME15</p>
        </div>
        
    </div>
    <div class="relative flex w-full h-24">

        <!-- Left Column -->
        <div class="bg-background-color-4 w-full flex items-center py-4 pl-16">
            <div class="relative w-3/5">
                <input type="text" 
                    class="w-full p-3 pl-10 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    placeholder="Buscar por marca, artículo..."
                >
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 w-5 h-5" 
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                </svg>
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
            <img src="{{ asset('images/logo/bags_and_tea_logo.svg') }}" class="mx-16 my-4 w-36 h-22"> 
        </div>

    </div>
    <div class="w-full border-t-2 border-t-theme-color-1">

    </div>
    <div class="h-24 bg-background-color-4 flex items-center px-32">
        <nav class="flex justify-between items-center gap-4 bg-background-color-4 p-4 w-full">
            <a href="#" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-1') }}</a>
            <a href="#" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-2') }}</a>
            <a href="#" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-3') }}</a>
            <a href="#" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-4') }}</a>
            <a href="#" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-5') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-6') }}</a>
            <a href="#" class="flex-1 text-center text-color-2 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-7') }}</a>
        </nav>
    </div>


</div>
</div>


