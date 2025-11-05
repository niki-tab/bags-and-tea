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

<div class="bg-[#F6F0ED] py-12 px-2 md:px-4 lg:px-6">

    <div class="max-w-6xl mx-auto px-4 md:px-0">

        
        <div class="text-center mb-12">
            <h1 class="mt-[20px] mb-[40px] text-4xl md:text-5xl font-rosaline text-[#482626]">
                {{ trans('pages/lifetime-guarantee.main_title') }}
            </h1>

            <p class="text-left text-lg font-robotoCondensed text-[#482626] mb-8">
                {{ trans('pages/lifetime-guarantee.main_description') }}
            </p>

            <p class="text-left text-lg font-robotoCondensed text-[#482626] mb-8">
                {{ trans('pages/lifetime-guarantee.main_description_1') }}
            </p>

            <img
                src="{{ asset('images/lifetime-guarantee/MaskGroup.png') }}"
                alt="Bags & Tea"
                class="mx-auto w-full h-auto"
            >
        </div>

        <div class="text-center mb-12">
            <h2 class="text-left mt-[70px] mb-[30px] text-3xl md:text-4xl font-rosaline text-[#AC2231]">
                {{ trans('pages/lifetime-guarantee.main_title_2') }}
            </h2>

            <p class="text-left text-lg font-robotoCondensed text-[#482626] mb-8">
                {!! trans('pages/lifetime-guarantee.main_description_2') !!}
            </p>
            <p class="text-left text-lg font-robotoCondensed text-[#482626] mb-8">
                {!! trans('pages/lifetime-guarantee.main_description_3') !!}
            </p>
            <p class="text-left text-lg font-robotoCondensed text-[#482626] mb-8">
                {!! trans('pages/lifetime-guarantee.main_description_4') !!}
            </p>

            <div class="flex flex-col md:flex-row gap-6 justify-center items-start">
                <img
                    src="{{ asset('images/lifetime-guarantee/MaskGroup_1.webp') }}"
                    alt="Bags & Tea"
                    class="w-full md:w-[60%] h-[200px] md:h-[500px] object-cover"
                >
                <img
                    src="{{ asset('images/lifetime-guarantee/MaskGroup_2.webp') }}"
                    alt="Bags & Tea"
                    class="w-full md:w-[38%] h-[200px] md:h-[500px] object-cover"
                >
            </div>
        </div>
    </div> 

</div>

@endsection

@metadata
