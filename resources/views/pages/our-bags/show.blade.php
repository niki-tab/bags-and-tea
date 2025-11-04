@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4
@endsection

@section('footer-desktop-class')
bg-background-color-4
@endsection

@section('footer-mobile-class')
bg-background-color-4
@endsection

<meta name="robots" content="index, follow" />

@section('content')

<div class="bg-[#F6F0ED] py-12 px-4 md:px-16">
    <div class="max-w-7xl mx-auto">
        <!-- Título Principal -->
        <div class="text-center mb-12">
            <h1 class="mt-[20px] mb-[40px] text-4xl md:text-5xl font-rosaline text-[#482626]">
                {{ trans('pages/our-bags.main_title') }}
            </h1>

            <p class="text-left text-lg font-robotoCondensed text-[#482626] max-w-4xl mx-auto mb-8">
                {{ trans('pages/our-bags.main_description') }}
            </p>

            <p class="text-left text-lg font-robotoCondensed text-[#482626] max-w-4xl mx-auto mb-8">
                {{ trans('pages/our-bags.main_description_1') }}
            </p>

            <img
                src="{{ asset('images/our-bags/MaskGroup.png') }}"
                alt="Bags & Tea"
                class="mx-auto max-w-full h-auto"
            >
        </div>

        <div class="text-center mb-12">
            <h2 class="text-left max-w-4xl mx-auto mt-[70px] mb-[30px] text-3xl md:text-4xl font-rosaline text-[#AC2231]">
                {{ trans('pages/our-bags.main_title_2') }}
            </h2>
            <p class="text-left text-lg font-robotoCondensed text-[#482626] max-w-4xl mx-auto mb-8">
                {!! trans('pages/our-bags.main_description_2') !!}
            </p>
            <p class="text-left text-lg font-robotoCondensed text-[#482626] max-w-4xl mx-auto mb-8">
                {!! trans('pages/our-bags.main_description_3') !!}
            </p>
            <p class="text-left text-lg font-robotoCondensed text-[#482626] max-w-4xl mx-auto mb-8">
                {!! trans('pages/our-bags.main_description_4') !!}
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
                <img
                    src="{{ asset('images/our-bags/MaskGroup_1.png') }}"
                    alt="Bags & Tea"
                    class="max-w-full h-auto"
                >
                <img
                    src="{{ asset('images/our-bags/MaskGroup_2.png') }}"
                    alt="Bags & Tea"
                    class="max-w-full h-auto"
                >
            </div>
        </div>
        
    </div>
</div>

<!-- Banner adicional -->

@endsection

@metadata
