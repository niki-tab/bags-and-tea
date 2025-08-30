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

<div class="bg-[#F6F0ED] py-12 px-4 md:px-16">
    <div class="max-w-7xl mx-auto">
        <!-- Título principal centrado -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-['Lovera'] text-[#482626] mb-6 tracking-widest">
                {{ __('pages/repair-your-bag.repair_subtitle') }}
            </h1>
            <p class="text-base text-[#482626] font-robotoCondensed font-regular max-w-4xl mx-auto">
                {{ __('pages/repair-your-bag.repair_description') }}
            </p>
        </div>
        
        <!-- Imagen principal centrada -->
        <div class="flex justify-center items-center mb-12">
            <img 
                src="{{ asset('images/repair-your-bag/repair-your-bag1.png') }}" 
                alt="Artesano reparando bolso" 
                class="w-full max-w-6xl h-auto object-cover"
            >
        </div>
        
        <!-- Sección inferior con título y descripción -->
        <div class="text-left mb-12 w-[65%] ml-[20%]">
            <h2 class="text-2xl font-['Lovera'] text-[#482626] mb-6 tracking-widest text-left">
                {{ __('pages/repair-your-bag.cta_title') }}
            </h2>
            <p class="text-base text-[#482626] font-robotoCondensed font-regular max-w-4xl mx-auto">
                {{ __('pages/repair-your-bag.cta_description') }}
            </p>
        </div>
        
        <!-- Dos imágenes lado a lado -->
        <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
            <div class="w-full md:w-1/2">
                <img 
                    src="{{ asset('images/repair-your-bag/repair-your-bag2.png') }}" 
                    alt="Máquina de coser industrial" 
                    class="w-full h-80 object-cover"
                >
            </div>
            <div class="w-full md:w-1/2">
                <img 
                    src="{{ asset('images/repair-your-bag/repair-your-bag3.png') }}" 
                    alt="Detalle de aguja y hilo" 
                    class="w-full h-80 object-cover"
                >
            </div>
        </div>
    </div>
    
    <!-- Formulario de reparación -->
    <div id="repair-form" class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="w-4/5 mx-auto">
            @livewire('crm/forms/show', ['formTitle' => 'Contacto', 'formIdentifier' => 'repair-your-bag', 'formButtonText' => 'Enviar solicitud', 'isTermsAndConditions' => true, 'isReceiveComercialInformation' => true])
        </div>
    </div>
</div>

@endsection

@metadata