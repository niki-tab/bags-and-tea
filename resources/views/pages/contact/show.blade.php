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
    <div class="w-full md:block hidden">
        <img 
            src="{{ asset('images/home/image54.png') }}" 
            alt="Bolso Bags and Tea" 
            class="w-full object-contain block mx-auto">
    </div>


    <div class="w-full h-60 md:hidden">
        <img 
            src="{{ asset('images/home/image54.png') }}" 
            alt="Bolso Bags and Tea" 
            class="w-full h-full object-cover mx-auto">
    </div>
    <div id="contact-form" class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto">
            @livewire('crm/forms/show', ['formTitle' => trans('pages/contact.form-title'), 'formIdentifier' => 'contact-us', 'formButtonText' => trans('pages/contact.form-button-text'), 'isTermsAndConditions' => true, 'isReceiveComercialInformation' => true])
        </div>
    </div>
</div>

@endsection

@metadata