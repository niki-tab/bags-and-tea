@extends('layouts.app')

@section('main-tag-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('footer-desktop-class')
bg-background-color-4  {{-- or any other Tailwind class you want for this specific page --}}
@endsection

@section('content')
    @livewire('blog/articles/show', ['articleSlug' => request()->route('articleSlug')])
@endsection

@section('floating-banner')
    <x-floating-banner
        title="{{ app()->getLocale() === 'es' ? 'Síguenos en Instagram para disfrutar de hasta un 20% de descuento en bolsos' : 'Follow us on Instagram to enjoy up to 20% off on bags' }}"
        titleMobile="{{ app()->getLocale() === 'es' ? 'Síguenos en Instagram para disfrutar de hasta un 20% de descuento en bolsos' : 'Follow us on Instagram to enjoy up to 20% off on bags' }}"
        buttonText="{{ app()->getLocale() === 'es' ? 'Síguenos' : 'Follow us' }}"
        buttonTextMobile="{{ app()->getLocale() === 'es' ? 'Síguenos' : 'Follow us' }}"
        buttonLink="https://www.instagram.com/bags.and.tea"
        :delay="10"
        image="{{ asset('images/banners/louis-vuitton-banner-2.webp') }}"
        imageMobile="{{ asset('images/banners/fendi-banner.webp') }}"
    />
@endsection

@metadata