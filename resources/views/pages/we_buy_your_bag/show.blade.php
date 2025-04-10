@extends('layouts.app')

@section('content')

<div class="w-full">
    <div class="flex flex-col md:flex-row h-auto md:h-96">
        <div class="w-full md:w-1/2 px-8 md:px-28 py-12 md:pt-20 bg-[#CB4853] text-white">
            <h1 class="text-4xl md:text-5xl font-['Lovera'] md:w-[55%]">
                {{ trans('pages/we-buy-your-bag.hero_title') }}
            </h1>
            <p class="mt-6 md:mt-8 font-mixed">
                {{ trans('pages/we-buy-your-bag.hero_description') }}
            </p>
            <button class="mt-6 md:mt-8 bg-black text-white px-8 md:px-12 py-2 md:py-3 rounded-full font-medium">
                {{ trans('pages/we-buy-your-bag.hero_button') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>
            </button>
        </div>
        <div class="w-full md:w-1/2 bg-[#DEA3A5] py-12 md:py-0">
            <img src="{{ asset('images/we_buy_your_bag/Bolso_YSL1.svg') }}" 
                alt="Luxury YSL Bag" 
                class="w-2/3 md:w-1/2 mx-auto md:mt-16">
        </div>
    </div>
    <div class="py-8 md:py-16 bg-[#F8F3F0]">
        <h2 class="text-center text-2xl md:text-4xl mb-8 md:mb-14 font-['Lovera']">{{ trans('pages/we-buy-your-bag.brands_title') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-7xl mx-auto mb-4 px-4 md:px-0">
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Hermes.svg') }}" alt="Luxury Hermes Bag" 
                    class="mx-auto my-8 md:my-10 w-2/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/yves-saint-laurent.svg') }}" alt="Luxury YSL Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Dior.svg') }}" alt="Luxury Dior Bag" 
                    class="mx-auto my-12 md:my-16 w-2/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Louis-Vuitton.svg') }}" alt="Luxury Louis Vuitton Bag" 
                    class="mx-auto my-[20%] md:my-[24%] w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Gucci.svg') }}" alt="Luxury Gucci Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Prada.svg') }}" alt="Luxury Prada Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Goyard.svg') }}" alt="Luxury Goyard Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Chanel.svg') }}" alt="Luxury Chanel Bag" 
                    class="mx-auto my-8 md:my-10 w-2/5">
            </div>
        </div>
    </div>
    <div class="bg-[#C8928A] text-white pb-14 text-center">
        <h2 class="mx-4 relative top-12 text-center text-4xl font-regular font-['Lovera']">{{ trans('pages/we-buy-your-bag.steps_title') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 md:px-16 py-12 md:py-4 md:h-[500px]">
            <!-- First item -->
            <div class="text-center md:text-left mx-8 md:mx-0">
                <div class="flex items-start gap-4">
                    <span class="text-[180px] md:text-[245px] text-[#482626] font-['Lovelina'] leading-none">{{ trans('pages/we-buy-your-bag.step_1_number') }}</span>
                    <h3 class="text-3xl md:text-4xl text-robotoCondensed font-regular mt-[70px] md:mt-28 w-3/4 md:w-full">
                        {{ trans('pages/we-buy-your-bag.step_1_title') }}
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular mt-4 md:mt-[-60px] pt-0 md:pt-20">
                    {{ trans('pages/we-buy-your-bag.step_1_description') }}
                </p>
            </div>
            <div class="text-center md:text-left mx-8 md:mx-0">
                <div class="flex items-start gap-4">
                    <span class="text-[180px] md:text-[245px] text-[#482626] font-['Lovelina'] leading-none">{{ trans('pages/we-buy-your-bag.step_2_number') }}</span>
                    <h3 class="text-3xl md:text-4xl text-robotoCondensed font-regular mt-[70px] md:mt-28 w-3/4 md:w-full">
                        {{ trans('pages/we-buy-your-bag.step_2_title') }}
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular mt-4 md:mt-[-60px] pt-0 md:pt-20">
                    {{ trans('pages/we-buy-your-bag.step_2_description') }}
                </p>
            </div>
            <div class="text-center md:text-left mx-8 md:mx-0">
                <div class="flex items-start gap-4">
                    <span class="text-[180px] md:text-[245px] text-[#482626] font-['Lovelina'] leading-none">{{ trans('pages/we-buy-your-bag.step_3_number') }}</span>
                    <h3 class="text-3xl md:text-4xl text-robotoCondensed font-regular mt-[70px] md:mt-28 w-3/4 md:w-full">
                        {{ trans('pages/we-buy-your-bag.step_3_title') }}
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular mt-4 md:mt-[-60px] pt-0 md:pt-20">
                    {{ trans('pages/we-buy-your-bag.step_3_description') }}
                </p>
            </div>
        </div>
        <button class="bg-black text-white px-12 py-3 rounded-full font-medium mx-auto">{{ trans('pages/we-buy-your-bag.hero_button') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;></button>
        
    </div>
    <div class="bg-[#F8F3F0] py-20">
        <div class="w-full md:w-full mx-auto bg-[#F8F3F0] relative">
            <div class="absolute bg-[#F8F3F0] p-4 w-2/5 left-52 top-16">
                <h2 class="text-center mx-auto w-1/2 font-['Lovera'] text-4xl text-[#482626]">{{ trans('pages/we-buy-your-bag.why_bag_question') }}</h2>
                <p class="mx-auto w-full text-[#482626] text-robotoCondensed mt-4">{{ trans('pages/we-buy-your-bag.why_bag_answer') }}</p>
            </div>
            <div class="flex justify-end mr-56"> <!-- Added this container -->
                <img src="{{ asset('images/we_buy_your_bag/group-142.svg') }}" 
                    alt="YSL bag" 
                    class="w-3/5"> <!-- Optional: adjust width as needed -->
            </div>
        </div>
        <div class="w-full md:w-full mx-auto bg-[#F8F3F0] relative mt-32">
            <div class="flex justify-start"> <!-- Added this container -->
                <img src="{{ asset('images/we_buy_your_bag/group-143.svg') }}" 
                    alt="YSL bag" 
                    class="w-3/5"> <!-- Optional: adjust width as needed -->
            </div>
            <div class="absolute bg-[#F8F3F0] p-4 w-2/5 right-52 bottom-32">
                <h2 class="text-center mx-auto w-3/4 font-['Lovera'] text-4xl text-[#482626]">{{ trans('pages/we-buy-your-bag.why-bags-and-tea-question') }}</h2>
                <p class="mx-auto w-full text-[#482626] text-robotoCondensed mt-4">{{ trans('pages/we-buy-your-bag.why-bags-and-tea-answer') }}</p>
            </div>
        </div>
    </div>
    </div>
        <!-- FAQs Section -->
        <div class="bg-[#3A1515] text-white py-12">
        <h2 class="mx-6 text-center text-4xl font-regular mb-10 md:mb-20 font-['Lovera']">{{ trans('pages/we-buy-your-bag.faq_title') }}</h2>
        <div class="max-w-7xl mx-auto space-y-4 pb-10 px-12 md:px-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-x-32 max-w-8xl mx-auto mb-4 mt-12 w-full">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="md:col-span-1">
                        <details class="pb-4 md:pb-6 border-b border-white">
                            <summary class="cursor-pointer flex items-center justify-between">
                                <h3 class="inline text-xl w-4/5">{{ trans("pages/we-buy-your-bag.faq_{$i}_question") }}</h3>
                                <span class="text-xl mr-4">+</span>
                            </summary>
                            <p class="mt-2">{{ trans("pages/we-buy-your-bag.faq_{$i}_answer") }}</p>
                        </details>  
                    </div>
                @endfor
            </div>
            
            <!-- Repeat for other FAQs -->
        </div>
    </div>

     <!-- Sell Your Bag Section -->
     <div>
        <div class="relative">
        <!-- Top half background -->
        <div class="absolute top-0 left-0 right-0 h-3/4 bg-[#DEA3A5]"></div>
        <!-- Bottom half background -->
        <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-[#F6F0ED]"></div>
        
        <!-- Content -->
        <div class="relative z-10">
            <div class="max-w-7xl">
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Image Section -->
                    <div class="w-full md:w-4/5 pt-8">
                        <img src="{{ asset('images/we_buy_your_bag/lv-bag-we-buy-your-bags.svg') }}" 
                            alt="Louis Vuitton Bag" 
                            class="w-full">
                    </div>
                    
                    <!-- Text Section -->
                    <div class="w-full md:w-[60%] bg-white p-8 md:p-12 relative bottom-[170px] md:bottom-[257px] md:left-[8%] bg-[#F6F0ED] border-l-[12px] border-b-[12px] border-[#BE6F62]">
                        <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mb-6">
                            {{ trans('pages/we-buy-your-bag.final_section_title') }}
                        </h2>
                        <p class="text-[#3A1515] mb-6">
                            {{ trans('pages/we-buy-your-bag.final_section_description_1') }}
                        </p>
                        <p class="text-[#3A1515] mb-6">
                            {{ trans('pages/we-buy-your-bag.final_section_description_2') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@metadata