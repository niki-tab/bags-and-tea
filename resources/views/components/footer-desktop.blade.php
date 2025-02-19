<div class="relative w-full h-[165px] bg-white">
    <!-- Brown Div (Overlapping the Orange Div) -->
    
</div>
<div class="relative w-full h-[500px] bg-background-color-3">
    <div class="absolute w-[94%] h-[620px] bg-background-color-1 left-1/2 transform -translate-x-1/2 bottom-[0] flex">
    <div class="w-2/5 flex flex-col justify-center items-center h-full">
        <img src="{{ asset('images/logo/bags_and_tea_logo_2.svg') }}" class="w-64 h-64 mb-12">    
        <!-- Rounded Button Below -->
        <a href="#" class="mb-24 px-12 py-2 font-robotoCondensed bg-background-color-2 text-color-4 rounded-full text-lg font-bold hover:bg-blue-600 transition">
            {{ trans('components/footer.button-sell-your-bag') }}
        </a>
    </div>
    <div class="w-1/5 flex justify-center"> 
        <nav class="flex flex-col space-y-8 w-full mt-12">
            <a href="#" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.footer-option-1') }}</a>
            <a href="#" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.footer-option-2') }}</a>
            <a href="#" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.footer-option-3') }}</a>
            <a href="#" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.footer-option-4') }}</a>
            <a href="#" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.footer-option-5') }}</a>
            <a href="#" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.footer-option-6') }}</a>
            
            <!-- Ensure the image is centered properly -->
            <div class="flex justify-center items-center">
                <img src="{{ asset('images/icons/RRSS_insta_b_3.svg') }}" class="w-8 h-8 mr-3 cursor-pointer">  
                <img src="{{ asset('images/icons/RRSS_facebook_b_2.svg') }}" class="w-8 h-8 ml-3 cursor-pointer">  
                  
            </div>
        </nav>
    </div>
    <div class="w-2/5 flex justify-center items-center">
        <div class="w-1/2 flex flex-col items-start">
            <p class="font-robotoCondensed text-xl font-bold">{{ trans('components/footer.text-newsletter-title') }}</p>
            <p class="mt-2 leading-loose">{{ trans('components/footer.text-newsletter-description') }}</p>
            <input type="text" class="font-robotoCondensed h-10 w-full my-10 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4" 
                placeholder="{{ trans('components/footer.placeholder-input-subscribe-to-newsletter') }}">
            <div class="w-full flex justify-center">
            <a href="#" class="border border-color-2 py-1 px-6 font-robotoCondensed bg-background-color-1 text-color-2 rounded-full text-lg font-regular hover:bg-blue-600 transition">
                {{ trans('components/footer.button-subscribe-to-newsletter') }}
            </a>
            </div>
        </div>
    </div>
    </div>
    <div class="absolute w-full h-12 bg-background-color-2 left-1/2 transform -translate-x-1/2 bottom-8 flex items-center">
        <nav class="flex justify-between items-center gap-4 py-4 w-2/5 mx-auto">
            <a href="#" class="flex-1 text-center text-color-1 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.subfooter-option-1') }}</a>
            <a href="#" class="flex-1 text-center text-color-1 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.subfooter-option-2') }}</a>
            <a href="#" class="flex-1 text-center text-color-1 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.subfooter-option-3') }}</a>
        </nav>
    </div>
</div>
