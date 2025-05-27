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
    <div class="relative w-full h-[500px] md:block hidden">
        <!-- Background Image -->
        <img 
            src="{{ asset('images/home/mask-group.svg') }}" 
            alt="Tienda de Bolsos de Lujo Bags and Tea" 
            class="w-full h-full object-cover">
    </div>
    <div class="relative w-full h-[300px] md:hidden">
        <!-- Background Image -->
        <img 
            src="{{ asset('images/home/mask-group.svg') }}" 
            alt="Tienda de Bolsos de Lujo Bags and Tea" 
            class="w-full h-full object-cover">

    </div>
    <div id="contact-form" class="bg-[#F6F0ED] py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto">
            @livewire('crm/forms/show', ['formTitle' => trans('pages/contact.form-title'), 'formIdentifier' => 'contact-us', 'formButtonText' => trans('pages/contact.form-button-text'), 'isTermsAndConditions' => true, 'isReceiveComercialInformation' => true])
        </div>
    </div>
</div>

@endsection

@metadata