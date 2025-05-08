@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

<meta name="robots" content="noindex, nofollow" />

@section('content')

@php
    $ourBrandsTitle = trans('pages/home.brands_title');
@endphp

@section('content')
<div>
    <div class="flex flex-col md:flex-row h-auto md:h-96">
        <div class="w-full md:w-1/2 bg-[#ffffff] flex items-center justify-center h-full py-12 md:py-0 pl-0 md:pl-10">
            <div class="w-3/4 mx-auto">
                <h1 class="text-4xl md:text-5xl font-['Lovera'] text-color-2">
                    {{ trans('pages/home.title-sell-your-bag') }}
                </h1>
                <p class="font-mixed text-color-2 mt-6 md:mt-8">
                    {{ trans('pages/home.description-sell-your-bag') }}   
                </p>
                <button class="mt-6 md:mt-8 bg-color-2 text-white px-8 md:px-12 py-2 md:py-3 rounded-full font-medium">
                    {{ trans('pages/home.button-sell-your-bag') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>
                </button>
            </div>


        </div>
        <div class="w-full md:w-1/2 bg-[#ffffff] py-12 md:py-0">
            <img src="{{ asset('images/we_buy_your_bag/Bolso_YSL1.svg') }}" 
                alt="Luxury YSL Bag" 
                class="w-2/3 md:w-1/2 mx-auto md:mt-16">
        </div>
    </div>
    <x-our-brands :title="$ourBrandsTitle"/>
    <div class="py-8 md:py-16 bg-color-3">
        <h2 class="text-center text-[#ffffff] text-2xl md:text-4xl mb-8 md:mb-14 font-['Lovera'] w-2/3 mx-auto">{{ trans('pages/home.title-avantage-buying-bags-and-tea') }} </h2>
        <p class="text-white w-2/3 mx-auto text-robotoCondensed font-light">{{ trans('pages/home.description-avantage-buying-bags-and-tea') }}</p>
    </div>
    <div class="pt-2">
        @livewire('blog/show', ['numberArticles' => 3])
    </div>
</div>

@endsection