@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-mobile-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

<meta name="robots" content="noindex, nofollow" />

@section('content')

@php
    $ourBrandsTitle = trans('pages/home.brands_title');
@endphp

<div class="bg-[#F6F0ED] py-12 px-4 md:px-16">
    <div class="max-w-7xl mx-auto">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <div class="flex gap-8">
                <!-- Columna izquierda: Nosotros + Imagen flotante -->
                <div class="flex-1">
                    <h2 class="text-4xl font-['Lovera'] text-[#482626] mb-6 ml-10 text-left tracking-widest mr-12">{{ __('pages/about-us.about_us_title') }}</h2>
                    <img 
                        src="{{ asset('images/about-us/nosotros-img21.png') }}" 
                        alt="Bolso" 
                        class="w-[450px] h-[300px] object-cover float-right ml-8 mb-4"
                    >
                    <p class="text-base font-['Lora']">
                        {!! nl2br(e(__('pages/about-us.about_us_description'))) !!}
                    </p>
                </div>
                <!-- Columna derecha: Valores -->
                <div class="w-1/3">
                    <h2 class="text-4xl font-['Lovera'] text-[#482626] ml-14 mb-6 text-left tracking-widest mr-28 w-[250px]">{{ __('pages/about-us.values_title') }}</h2>
                    <ul class="space-y-9 font-['Lora']">
                        <li class="flex items-start">
                            <img 
                                src="{{ asset('images/about-us/list.png') }}" 
                                class="w-4 h-4 mt-1 mr-2"
                                alt="Lista"
                            >
                            <span>{{ __('pages/about-us.value_authenticity') }}</span>
                        </li>
                        <li class="flex items-start">
                        <img 
                                src="{{ asset('images/about-us/list.png') }}" 
                                class="w-4 h-4 mt-1 mr-2"
                                alt="Lista"
                            >
                            <span>{{ __('pages/about-us.value_sustainability') }}</span>
                        </li>
                        <li class="flex items-start">
                        <img 
                                src="{{ asset('images/about-us/list.png') }}" 
                                class="w-4 h-4 mt-1 mr-2"
                                alt="Lista"
                            >
                            <span>{{ __('pages/about-us.value_trust') }}</span>
                        </li>
                        <li class="flex items-start">
                        <img 
                                src="{{ asset('images/about-us/list.png') }}" 
                                class="w-4 h-4 mt-1 mr-2"
                                alt="Lista"
                            >
                            <span>{{ __('pages/about-us.value_passion') }}</span>
                        </li>
                        <li class="flex items-start">
                        <img 
                                src="{{ asset('images/about-us/list.png') }}" 
                                class="w-4 h-4 mt-1 mr-2"
                                alt="Lista"
                            >
                            <span>{{ __('pages/about-us.value_customer_service') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Mobile Layout -->
        <div class="block md:hidden">
            <!-- Texto de Nosotros -->
            <div class="mb-8">
                <h2 class="text-4xl font-['Lovera'] text-[#482626] mb-6 text-center tracking-widest">{{ __('pages/about-us.about_us_title') }}</h2>
                <p class="text-base font-['Lora'] px-6">
                    {!! nl2br(e(__('pages/about-us.about_us_description'))) !!}
                </p>
            </div>
            
            <!-- Imagen después del texto -->
            <div class="flex justify-center items-center mb-8">
                <img 
                    src="{{ asset('images/about-us/nosotros-img21.png') }}" 
                    alt="Bolso" 
                    class="w-[450px] h-[300px] object-cover mx-auto"
                >
            </div>
            
            <!-- Valores -->
            <div class="mt-12">
                <h2 class="text-4xl font-['Lovera'] text-[#482626] mb-6 text-center tracking-widest">{{ __('pages/about-us.values_title') }}</h2>
                <ul class="space-y-9 font-['Lora']">
                    <li class="flex items-start">
                        <img 
                            src="{{ asset('images/about-us/list.png') }}" 
                            class="w-4 h-4 mt-1 mr-2"
                            alt="Lista"
                        >
                        <span>{{ __('pages/about-us.value_authenticity') }}</span>
                    </li>
                    <li class="flex items-start">
                    <img 
                            src="{{ asset('images/about-us/list.png') }}" 
                            class="w-4 h-4 mt-1 mr-2"
                            alt="Lista"
                        >
                        <span>{{ __('pages/about-us.value_sustainability') }}</span>
                    </li>
                    <li class="flex items-start">
                    <img 
                            src="{{ asset('images/about-us/list.png') }}" 
                            class="w-4 h-4 mt-1 mr-2"
                            alt="Lista"
                        >
                        <span>{{ __('pages/about-us.value_trust') }}</span>
                    </li>
                    <li class="flex items-start">
                    <img 
                            src="{{ asset('images/about-us/list.png') }}" 
                            class="w-4 h-4 mt-1 mr-2"
                            alt="Lista"
                        >
                        <span>{{ __('pages/about-us.value_passion') }}</span>
                    </li>
                    <li class="flex items-start">
                    <img 
                            src="{{ asset('images/about-us/list.png') }}" 
                            class="w-4 h-4 mt-1 mr-2"
                            alt="Lista"
                        >
                        <span>{{ __('pages/about-us.value_customer_service') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Banner Figma: La moda de hoy es circular -->
<div class="bg-[#EEC0C3] py-8 px-2 md:px-0">
    <h2 class="text-4xl font-['Lovera'] text-[#482626] text-center tracking-widest mb-6">
        {{ __('pages/about-us.circular_fashion_title') }}
    </h2>
    <div class="flex flex-col md:flex-row justify-center items-center gap-4 mb-6">
        <a href="{{ route(app()->getLocale() === 'es' ? 'we-buy-your-bag.show.es' : 'we-buy-your-bag.show.en', ['locale' => app()->getLocale()]) }}" class="bg-[#482626] text-white px-8 py-2 font-['Lora'] text-lg hover:bg-background-color-3 transition whitespace-nowrap">
            {{ __('pages/about-us.sell_your_bag_button') }}
        </a>
        <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" class="border border-[#B92334] text-[#B92334] px-8 py-2 font-['Lora'] text-lg hover:bg-background-color-3 hover:text-white transition whitespace-nowrap">
            {{ __('pages/about-us.buy_your_bag_button') }}
        </a>
    </div>
</div>
<div class="max-w-7xl mx-auto mt-8 px-4 mb-4">
    <p class="text-center md:text-center font-['Lora'] text-base md:text-lg px-6 text-justify md:text-center">
        {{ __('pages/about-us.circular_fashion_banner') }}
    </p>
</div>

@endsection

@metadata