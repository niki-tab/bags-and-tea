<div class="relative hidden md:block @yield('footer-desktop-class', 'bg-white')">
    <div class="relative">
        <div class="relative w-[94%] h-[480px] bg-background-color-1 left-1/2 transform -translate-x-1/2 bottom-[0] flex z-10">
    
            <div class="w-2/5 flex flex-col justify-center items-center h-full">
                <img src="{{ asset('images/logo/bags_and_tea_logo_2.svg') }}" class="w-64 h-64 -mt-6">    
                <!-- Rounded Button Below -->
                <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="mb-24 px-12 pt-2 pb-1.5 font-robotoCondensed bg-background-color-2 text-color-4 rounded-full text-lg font-regular hover:bg-background-color-5 transition">
                    {{ trans('components/footer.button-sell-your-bag') }}
                </a>
            </div>
            <div class="w-1/5 flex justify-center"> 
                <nav class="flex flex-col space-y-4 w-full mt-10">
                    <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-1') }}</a>
                    <a href="{{ route(app()->getLocale() === 'es' ? 'our-bags.show.es' : 'our-bags.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-2') }}</a>
                    <a href="{{ route(app()->getLocale() === 'es' ? 'about-us.show.es' : 'about-us.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-3') }}</a>
                    <a href="{{ route(app()->getLocale() === 'es' ? 'blog.show.en-es' : 'blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-4') }}</a>
                    <a href="{{ route(app()->getLocale() === 'es' ? 'contact.send.es' : 'contact.send.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-5') }}</a>
                    <a href="{{ route(app()->getLocale() === 'es' ? 'privacy.show.es' : 'privacy.show.en', ['locale' => app()->getLocale()]) }}" class="text-center text-text-color-4 font-robotoCondensed text-lg font-medium hover:text-color-5">{{ trans('components/footer.footer-option-6') }}</a>
                    <!-- Ensure the image is centered properly -->
                    <div class="flex justify-center items-center pt-4 gap-4">
                        <a target="_blank" href="https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr"><img src="{{ asset('images/icons/RRSS_insta_b_5.svg') }}" class="w-8 h-8 cursor-pointer"></a>  
                        <a target="_blank" href="https://www.vinted.es/member/250362636-bagsandtea"><img src="{{ asset('images/icons/icon_vinted.svg') }}" class="w-8 h-8 cursor-pointer"></a>
                        <a target="_blank" href="https://es.vestiairecollective.com/profile/30176798/?sortBy=relevance&tab=items-for-sale"><img src="{{ asset('images/icons/icon_vestaire_collective.svg') }}" class="w-8 h-8 cursor-pointer"></a>
                    </div>
                </nav>
            </div>
            <div class="w-2/5 flex justify-center items-center">
                <div class="w-1/2 flex flex-col items-start mb-20">
                    <p class="text-color-2 font-robotoCondensed text-xl font-bold">{{ trans('components/footer.text-newsletter-title') }}</p>
                    <p class="text-color-2 mt-2 leading-loose">{{ trans('components/footer.text-newsletter-description') }}</p>
                    <input type="text" class="font-robotoCondensed h-8 w-full mt-6 mb-6 bg-transparent border-b border-color-2 placeholder-color-2 placeholder-font-robotoCondensed pl-4 focus:outline-none focus:ring-0" 
                        placeholder="{{ trans('components/footer.placeholder-input-subscribe-to-newsletter') }}">
                    <div class="w-full flex justify-center pt-4">
                        <a href="#" class="border border-color-2 py-1 px-6 font-robotoCondensed bg-background-color-1 text-color-2 rounded-full text-lg font-regular hover:bg-background-color-2 hover:text-white transition">
                            {{ trans('components/footer.button-subscribe-to-newsletter') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <div class="absolute top-28 w-full h-[368px] bg-background-color-3 z-0">

        </div>
        <div class="absolute w-full h-12 bg-background-color-2 left-1/2 transform -translate-x-1/2 bottom-8 flex items-center z-20">
            <nav class="flex justify-between items-center gap-4 py-4 w-2/5 mx-auto">

                <a href="{{ route(app()->getLocale() === 'es' ? 'privacy.show.es' : 'privacy.show.en', ['locale' => app()->getLocale()]) }}"
                        class="text-center font-robotoCondensed text-lg font-medium 
                                {{ request()->routeIs('privacy.show.es', 'privacy.show.en') 
                                ? 'text-color-5' : 'text-color-1 hover:text-color-3' }}">
                        {{ trans('components/footer.subfooter-option-1') }}
                </a>

                
                

                <a  href="{{ route(app()->getLocale() === 'es' ? 'cookies.show.en-es' : 'cookies.show.en-es', ['locale' => app()->getLocale()]) }}"
                    class=  "text-center font-robotoCondensed text-lg font-medium 
                            {{ request()->routeIs('cookies.show.en-es') 
                            ? 'text-color-5' : 'text-color-1 hover:text-color-3' }}">              
                    {{ trans('components/footer.subfooter-option-2') }}
                </a>
            </nav>
        </div>
    </div>
</div>