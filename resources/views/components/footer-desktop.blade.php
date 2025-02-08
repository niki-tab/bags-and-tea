<div class="relative w-full h-[165px] bg-white">
    <!-- Brown Div (Overlapping the Orange Div) -->
    
</div>
<div class="relative w-full h-[500px] bg-background-color-3">
    <div class="absolute w-[94%] h-[620px] bg-background-color-2 left-1/2 transform -translate-x-1/2 bottom-[0] flex">
    <div class="w-2/5 flex flex-col justify-center items-center h-full">
        <img src="{{ asset('images/logo/logo_footer.svg') }}" class="w-64 h-64 mb-12">    
        <!-- Rounded Button Below -->
        <a href="#" class="mb-24 px-12 py-2 font-robotoCondensed bg-background-color-3 text-text-color-3 rounded-full text-lg font-bold hover:bg-blue-600 transition">
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
                <img src="{{ asset('images/icons/RRSS_group_2.svg') }}" class="w-16 h-16">    
            </div>
        </nav>
    </div>
    <div class="w-2/5 flex items-center">

    </div>
    </div>
    <div class="absolute w-full h-24 bg-background-color-4 left-1/2 transform -translate-x-1/2 bottom-8 flex items-center">
        <nav class="flex justify-between items-center gap-4 py-4 w-2/5 mx-auto">
            <a href="#" class="flex-1 text-center text-text-color-3 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.subfooter-option-1') }}</a>
            <a href="#" class="flex-1 text-center text-text-color-3 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.subfooter-option-2') }}</a>
            <a href="#" class="flex-1 text-center text-text-color-3 font-robotoCondensed text-lg font-medium hover:text-blue-500">{{ trans('components/footer.subfooter-option-3') }}</a>
        </nav>
    </div>
</div>
