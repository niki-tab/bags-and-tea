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


@section('content')

<div class="bg-[#F6F0ED] py-12 px-4 md:px-16">
    <div class="max-w-7xl mx-auto flex flex-col">
        <!-- Título principal centrado -->
        <div class="text-center mb-8 order-2 md:order-1">
            <h1 class="text-4xl font-['Lovera'] text-[#482626] mb-6 tracking-widest">
                {{ __('pages/repair-your-bag.repair_subtitle') }}
            </h1>
            <p class="text-base text-[#482626] font-robotoCondensed font-regular max-w-4xl mx-auto">
                {{ __('pages/repair-your-bag.repair_description') }}
            </p>
        </div>
        
        <!-- Imagen principal centrada -->
        <div class="flex justify-center items-center mb-12 order-1 md:order-2">
            <img 
                src="{{ asset('images/repair-your-bag/repair-your-bag1.png') }}" 
                alt="Artesano reparando bolso" 
                class="w-full max-w-5xl h-auto object-cover"
                loading="lazy" decoding="async" sizes="(min-width: 768px) 1024px, 90vw"
            >
        </div>
        
        <!-- Sección inferior con título y descripción (desktop) -->
        <div class="hidden md:block text-center mb-12 w-[65%] mx-auto md:order-3">
            <h2 class="text-3xl font-['Lovera'] text-[#482626] mb-6 tracking-widest text-center">
                {{ __('pages/repair-your-bag.cta_title') }}
            </h2>
            <p class="text-base text-[#482626] font-robotoCondensed font-regular max-w-4xl mx-auto">
                {{ __('pages/repair-your-bag.cta_description') }}
            </p>
        </div>
        
        <!-- Dos imágenes lado a lado -->
        <div class="flex flex-col md:flex-row gap-2 justify-center items-center order-4 md:order-4">
            <div class="w-full md:w-1/2 flex items-center justify-center">
                <img 
                    src="{{ asset('images/repair-your-bag/repair-your-bag2.png') }}" 
                    alt="Máquina de coser industrial" 
                    class="w-auto h-auto object-contain"
                    loading="lazy" decoding="async"
                >
            </div>

            <!-- CTA móvil entre las dos imágenes -->
            <div class="block md:hidden text-center mb-8 mx-8 mt-8">
                <h2 class="text-2xl font-['Lovera'] text-[#482626] mb-4 tracking-widest">
                    {{ __('pages/repair-your-bag.cta_title') }}
                </h2>
                <p class="text-base text-[#482626] font-robotoCondensed font-regular text-justify">
                    {{ __('pages/repair-your-bag.cta_description') }}
                </p>
            </div>

            <div class="w-full md:w-1/2 flex items-center justify-center">
                <img 
                    src="{{ asset('images/repair-your-bag/repair-your-bag3.png') }}" 
                    alt="Detalle de aguja y hilo" 
                    class="w-auto h-auto object-contain"
                    loading="lazy" decoding="async"
                >
            </div>
        </div>
    </div>
    
    <!-- Formulario de reparación -->
    <div id="repair-form" class="bg-[#F6F0ED] py-8 md:py-20">
        <div class="border-[4px] border-[#3A1515] w-4/5 mx-auto">
            @livewire('crm/forms/show', ['formTitle' => 'Contacto', 'formIdentifier' => 'repair-your-bag', 'formButtonText' => 'Enviar solicitud', 'isTermsAndConditions' => true, 'isReceiveComercialInformation' => true])
        </div>
    </div>
</div>

@endsection

@metadata