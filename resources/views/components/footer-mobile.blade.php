<div class="md:hidden relative items-center justify-content @yield('footer-mobile-class', 'bg-white')">
    <div class="h-[160px]">
        
    </div>
    <div class="bg-background-color-3 h-[1320px]">
        
    </div>
    <div class="bg-background-color-1 h-[1480px] absolute bottom-0 w-11/12 inset-x-0 mx-auto">
        <img src="{{ asset('images/logo/bags_and_tea_logo_2.svg') }}" class="w-64 h-64 mt-6 mx-auto">    
        <!-- Rounded Button Below -->
        <div class="flex justify-center">
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="py-3 px-16 font-robotoCondensed bg-background-color-2 text-color-4 rounded-full text-lg font-regular hover:bg-background-color-5 transition mx-auto">
            {{ trans('components/footer.button-sell-your-bag') }}
            </a>
        </div>
        <div class="w-11/12 flex flex-col items-start mb-16 mx-auto mt-14 bg-[#D29289] p-10">
            <p class="text-color-2 font-robotoCondensed text-xl font-bold">{{ trans('components/footer.text-newsletter-title') }}</p>
            <p class="mt-2 leading-loose">{{ trans('components/footer.text-newsletter-description') }}</p>
            <input type="text" class="font-robotoCondensed h-8 w-full mt-6 mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0" 
                placeholder="{{ trans('components/footer.placeholder-input-subscribe-to-newsletter') }}">
            <div class="w-full flex justify-center pt-4">
                <a href="#" class="border-2 border-color-2 py-1 px-6 font-robotoCondensed bg-[#D29289] text-color-2 rounded-full text-lg font-medium hover:bg-background-color-2 hover:text-white transition">
                    {{ trans('components/footer.button-subscribe-to-newsletter') }}
                </a>
            </div>
        </div>
        <nav class="flex flex-col space-y-10 w-full mt-10">
            <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-1') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-2') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-3') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-4') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-5') }}</a>
            <a href="{{ route(app()->getLocale() === 'es' ? 'privacy.show.es' : 'privacy.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-6') }}</a>
            <!-- Ensure the image is centered properly -->
        </nav>
        <div class="flex gap-4 justify-center items-center mt-14">
            <a target="_blank" href="https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr"><img src="{{ asset('images/icons/RRSS_insta_b_5.svg') }}" class="w-7 h-7 cursor-pointer"></a>  
        </div>
    </div>
    <div class="bg-background-color-2 h-16 absolute bottom-14 w-full flex">
        <nav class="flex justify-between items-center gap-2 py-4 mx-auto w-full">
            <a
                href="{{ route(app()->getLocale() === 'es' ? 'privacy.show.es' : 'privacy.show.en', ['locale' => app()->getLocale()]) }}"
                 class="mx-auto text-center font-robotoCondensed text-lg font-medium
                        {{ request()->routeIs('privacy.show.es', 'privacy.show.en')
                        ? 'text-color-5' : 'text-color-1 hover:text-color-3' }}">

                {{ trans('components/footer.subfooter-option-1') }}
            </a>

            <a
                href="{{ route(app()->getLocale() === 'es' ? 'lifetime-guarantee.show.es' : 'lifetime-guarantee.show.en', ['locale' => app()->getLocale()]) }}"
                 class="mr-[35px] mx-auto text-center font-robotoCondensed text-lg font-medium
                        {{ request()->routeIs('lifetime-guarantee.show.es', 'lifetime-guarantee.show.en')
                        ? 'text-white' : 'text-white hover:text-color-3' }}">

                {{ trans('components/footer.subfooter-option-3') }}
            </a>

            <a
                href="{{ route(app()->getLocale() === 'es' ? 'cookies.show.en-es' : 'cookies.show.en-es', ['locale' => app()->getLocale()]) }}"
                 class="mx-auto text-center font-robotoCondensed text-lg font-medium
                        {{ request()->routeIs('cookies.show.en-es')
                        ? 'text-color-5' : 'text-color-1 hover:text-color-3' }}">
                {{ trans('components/footer.subfooter-option-2') }}
            </a>

        </nav>
    </div>
</div>