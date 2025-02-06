<div>
    <div class="h-12 bg-background-color-1 pl-32 flex">
        <div class="flex-1 flex gap-4 pt-2">
            <img src="{{ asset('images/icons/instagram_icon.png') }}" class="w-8 h-8">    
            <img src="{{ asset('images/icons/facebook_icon.png') }}" class="w-8 h-8">
        </div>
        <div class="flex-1 flex items-center justify-center">
            <p class="text-theme-color-2 font-robotoCondensed font-bold">¿1ª compra? -15% con el código WELCOME15</p>
        </div>
        
    </div>
    <div class="relative flex w-full h-24">

        <!-- Left Column -->
        <div class="bg-white w-1/2 flex items-center py-4 pl-16">
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
        <div class="w-1/2 bg-white"></div>

        <!-- Centered Image -->
        <div class="absolute left-1/2 top-[-20%] w-1/8 h-[120%] bg-white transform -translate-x-1/2">
            <img src="{{ asset('images/logo/bags_and_tea_logo.png') }}" class="mx-16 my-4 w-36 h-22"> 
        </div>

    </div>
    <div class="w-full border-t-2 border-t-theme-color-1">

    </div>
    <div class="h-24 bg-white flex items-center px-32">
        <nav class="flex justify-between items-center gap-4 bg-white p-4 w-full">
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-1') }}</a>
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-2') }}</a>
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-3') }}</a>
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-4') }}</a>
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-5') }}</a>
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-6') }}</a>
            <a href="#" class="flex-1 text-center text-black font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/header.menu-option-7') }}</a>
        </nav>
    </div>


</div>
</div>


