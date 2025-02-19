@extends('layouts.app')
@metadata
<meta name="robots" content="noindex, nofollow" />
@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden md:px-16">
    <div class="relative z-10 container mx-auto px-6 py-16 flex flex-col items-center text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ trans('pages/home.hero_title') }}</h1>
        <h2 class="text-lg md:text-2xl mb-8 w-9/12">{{ trans('pages/home.hero_subtitle') }}</h2>
        <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" class="bg-button-color-1 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-sky-800 hover:text-button-color-1-hover">{{ trans('pages/home.shop_now') }}</a>
    </div>
</section>

<!-- About Us Section -->
<section class="py-16 bg-white md:px-36">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-3xl font-bold mb-4">{{ trans('pages/home.about_title') }}</h1>
        <h2 class="text-lg mb-8">{{ trans('pages/home.about_description_1') }}</h2>
        <h2 class="text-lg mb-8 font-bold">{{ trans('pages/home.about_description_2') }}</h2>
    </div>
</section>

@endsection