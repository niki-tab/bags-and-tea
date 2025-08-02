@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('content')

@php
    $ourBrandsTitle = trans('pages/home.brands_title');
@endphp

@section('content')

@php
if(request()->route('bagName')){

    $bagName = request()->route('bagName');

    $translationKey = 'pages/home.faq_title-' . $bagName;
        $stringFaqTitle = Lang::has($translationKey) 
            ? trans($translationKey) 
            : trans('pages/home.faq_title');

    $iBagName = "-" . $bagName;

}else{

    $stringFaqTitle = trans('pages/we-buy-your-bag.faq_title');
    $iBagName = "";

}
@endphp

<div>
    <div class="relative w-full h-[700px] md:block hidden">
        <!-- Background Image -->
        <img 
            src="{{ asset('images/home/frame-home-1.svg') }}" 
            alt="Tienda de Bolsos de Lujo Bags and Tea" 
            class="w-full h-full object-cover">

        <!-- Text Content -->
        <div class="absolute left-20 top-1/2 transform -translate-y-1/2 text-white">
            <h1 class="text-white text-7xl font-['Lovera'] mb-10">{{ trans('pages/home.headline-1') }}</h1>
            <h2 class="text-white text-5xl font-robotoCondensed font-light">{{ trans('pages/home.headline-2') }}</h2>
        </div>
    </div>
    <div class="relative w-full h-[500px] md:hidden">
        <!-- Background Image -->
        <img 
            src="{{ asset('images/home/mask-group.svg') }}" 
            alt="Tienda de Bolsos de Lujo Bags and Tea" 
            class="w-full h-full object-cover">

        <!-- Centered and Wider Text Content -->
        <div class="absolute top-2/3 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-4xl px-4 text-white text-center">
            <h1 class="text-white text-5xl font-['Lovera'] mb-10">
                {{ trans('pages/home.headline-1') }}
            </h1>
            <h2 class="text-white text-3xl font-robotoCondensed font-light">
                {{ trans('pages/home.headline-2') }}
            </h2>
        </div>
    </div>

    <div class="flex flex-col md:flex-row h-auto md:h-96">
        <div class="w-full md:w-1/2 bg-[#ffffff] flex items-center justify-center h-full py-12 md:py-0 pl-0 md:pl-10">
            <div class="w-3/4 mx-auto">
                <h1 class="text-4xl md:text-5xl font-['Lovera'] text-color-2 text-center md:text-left">
                    {{ trans('pages/home.title-sell-your-bag') }}
                </h1>
                <p class="font-mixed text-color-2 mt-6 md:mt-8">
                    {{ trans('pages/home.description-sell-your-bag') }}   
                </p>
                <button class="mt-6 md:mt-8 bg-color-2 text-white px-8 md:px-12 py-2 md:py-3 rounded-full font-medium block mx-auto md:mx-0">
                    {{ trans('pages/home.button-sell-your-bag') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>
                </button>
            </div>
        </div>
        <div class="w-full md:w-1/2 bg-[#ffffff] py-12 md:py-0 md:flex md:items-center md:justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Bolso_YSL1.svg') }}" 
                alt="Luxury YSL Bag" 
                class="w-2/3 md:w-1/2 mx-auto"
            >
        </div>
    </div>

    <x-our-brands :title="$ourBrandsTitle"/>
    <div class="py-8 md:py-16 bg-color-3">
        <h2 class="text-center text-[#ffffff] text-2xl md:text-4xl mb-8 md:mb-14 font-['Lovera'] w-2/3 mx-auto">{{ trans('pages/home.title-avantage-buying-bags-and-tea') }} </h2>
        <p class="text-white w-2/3 mx-auto text-robotoCondensed font-light">{{ trans('pages/home.description-avantage-buying-bags-and-tea') }}</p>
    </div>
    <div class="py-8 md:py-16 bg-[#F8F3F0] px-4 lg:px-52">
        <h2 class="text-center text-color-2 text-2xl md:text-4xl mb-8 md:mb-14 font-['Lovera'] w-2/3 mx-auto">{{ trans('pages/home.title-featured-products') }} </h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 lg:gap-10">
            @foreach($featuredProducts as $product)
                <div class="bg-white shadow-lg overflow-hidden hover:shadow-xl transition flex flex-col">
                    <div class="aspect-square relative">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="p-3 md:p-6 mt-3 md:mt-6">
                        <h2 class="text-center text-sm md:text-xl font-robotoCondensed font-light text-color-2 mb-2">{{ $product['name'] }}</h2>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center">
            <a href="{{ app()->getLocale() == 'es' ? route('shop.show.es', ['locale' => app()->getLocale()]) : route('shop.show.en', ['locale' => app()->getLocale()]) }}" class="mt-12 md:mt-12 bg-transparent text-color-2 px-4 md:px-4 py-2 md:py-2 font-medium border border-color-2 font-['Lovera'] inline-block">
                {{ trans('pages/home.button-view-all-products') }}
            </a>
        </div>
    </div>

    <div class="py-4 sm:py-6 md:py-8 lg:py-12 xl:py-16 bg-[#F8F3F0] px-2 sm:px-4 lg:px-52">
        <div class="flex flex-col md:flex-row h-auto md:h-[250px] lg:h-[280px] xl:h-[320px] 2xl:h-[400px] w-full mx-auto mb-6 sm:mb-8 lg:mb-10">
            <!-- Imagen -->
            <div class="w-full md:w-1/2 bg-white flex items-center justify-center shadow-lg">
                <img src="{{ asset('images/home/image49.png') }}" 
                    alt="Luxury YSL Bag" 
                    class="w-full h-48 sm:h-56 md:h-full object-cover">
            </div>
            <!-- Contenido de texto -->
            <div class="w-full md:w-1/2 bg-white flex items-center justify-center shadow-lg">
                <div class="w-full px-3 py-4 sm:px-4 sm:py-5 md:px-4 md:py-3 lg:px-6 lg:py-4 xl:px-8 xl:py-0 2xl:px-10">
                    <h1 class="text-[#482626] text-lg sm:text-xl md:text-xl lg:text-2xl xl:text-3xl 2xl:text-4xl font-['Lovera'] text-center leading-tight">
                        {{ trans('pages/home.title-certify-your-bag') }}
                    </h1>
                    <p class="font-mixed text-color-2 mt-2 sm:mt-3 md:mt-2 lg:mt-3 xl:mt-4 2xl:mt-6 text-xs sm:text-sm md:text-xs lg:text-sm xl:text-base 2xl:text-lg text-center leading-relaxed">
                        {{ trans('pages/home.description-certify-your-bag') }}   
                    </p>
                    <div class="flex justify-center mt-3 sm:mt-4 md:mt-3 lg:mt-4 xl:mt-5 2xl:mt-6">
                        <button class="bg-color-2 text-white px-3 sm:px-4 md:px-4 lg:px-5 xl:px-8 2xl:px-12 py-1.5 sm:py-2 md:py-1.5 lg:py-2 xl:py-2.5 2xl:py-3 rounded-full font-medium flex items-center gap-1 sm:gap-1.5 md:gap-1 lg:gap-1.5 xl:gap-2 text-xs sm:text-sm md:text-xs lg:text-sm xl:text-base hover:bg-opacity-90 transition-colors">
                            {{ trans('pages/home.button-certify-your-bag') }} <span class="text-xs sm:text-sm md:text-xs lg:text-sm xl:text-base 2xl:text-lg font-bold ml-0.5 sm:ml-1 md:ml-0.5 lg:ml-1 xl:ml-2">&gt;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQs Section -->
    <div class="bg-[#3A1515] text-white py-12">
        <h2 class="mx-6 text-center text-4xl font-regular mb-10 md:mb-20 font-['Lovera']">{{ $stringFaqTitle }}</h2>
        <div class="mx-auto space-y-4 pb-10 px-12 md:px-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-x-32 max-w-8xl mx-auto mb-4 mt-12 w-full">
                @for ($i = 1; $i <= 10; $i++)
                    <div class="md:col-span-1">
                        <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer flex items-center justify-between">

                            @if(Lang::has("pages/home.faq_{$i}_question{$iBagName}"))
                                <h3 class="inline text-xl w-4/5">{{ trans("pages/home.faq_{$i}_question{$iBagName}") }}</h3>
                            @else
                                <h3 class="inline text-xl w-4/5">{{ trans("pages/home.faq_{$i}_question") }}</h3>
                            @endif

                            <span class="text-xl mr-4">+</span>
                            </summary>
                            @if(Lang::has("pages/home.faq_{$i}_answer{$iBagName}"))
                                <p class="mt-2">{!! trans("pages/home.faq_{$i}_answer{$iBagName}") !!}</p>
                            @else
                                <p class="mt-2">{!! trans("pages/home.faq_{$i}_answer") !!}</p>
                            @endif
                            
                        </details>  
                    </div>
                @endfor
            </div>
            
            <!-- Repeat for other FAQs -->
        </div>
    </div>
    <div class="pt-2">
        @livewire('blog/show', ['numberArticles' => 3])
        <div class="flex justify-center">
            <a href="{{ route('blog.show.en-es', ['locale' => app()->getLocale()]) }}" class="mt-12 md:mt-12 bg-transparent text-color-2 px-4 md:px-4 py-2 md:py-2 font-medium border border-color-2 font-['Lovera'] inline-block">
                {{ trans('pages/home.button-view-all-products') }}
            </a>
        </div>
    </div>
</div>

@endsection

@metadata