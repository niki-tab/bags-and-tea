@extends('layouts.app')
@section('content')

<div class="w-full">
    <div class="flex h-96">
        <div class="w-1/2 px-28 pt-20 bg-[#CB4853] text-white">
            <h1 class="text-5xl font-robotoCondensed font-regular w-[55%]">Vende tu bolso de lujo</h1>
            <p class="mt-8 font-mixed">Bienvenida a B&T, el lugar perfecto par vender, comprar o alquilar artículos de lujo exclusivos, desde un icono atemporal hasta el último it bag.</p>
            <button class="mt-8 bg-black text-white px-12 py-3 rounded-full font-medium">Ir al formulario &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;></button>
        </div>
        <div class="w-1/2 bg-[#DEA3A5]">
            <img src="{{ asset('images/we_buy_your_bag/Bolso_YSL1.svg') }}" alt="Luxury YSL Bag" class="w-1/2 mx-auto mt-16">
        </div>
    </div>
    <div class="py-16 bg-[#F8F3F0]">
        <h2 class="text-center text-4xl mb-14 font-regular font-robotoCondensed">COMPRAMOS TU BOLSO DE LUJO</h2>
        <div class="grid grid-cols-4 gap-8 max-w-7xl mx-auto mb-4">
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Hermes.svg') }}" alt="Luxury Hermes Bag" class="mx-auto my-10 w-2/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/yves-saint-laurent.svg') }}" alt="Luxury YSL Bag" class="mx-auto my-16 w-3/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Dior.svg') }}" alt="Luxury Dior Bag" class="mx-auto my-16 w-2/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Louis-Vuitton.svg') }}" alt="Luxury Louis Vuitton Bag" class="mx-auto my-[24%] w-3/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Gucci.svg') }}" alt="Luxury Gucci Bag" class="mx-auto my-16 w-3/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Prada.svg') }}" alt="Luxury Prada Bag" class="mx-auto my-16 w-3/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Goyard.svg') }}" alt="Luxury Goyard Bag" class="mx-auto my-16 w-3/5"></div>
            <div class="bg-[#E3D4CB] text-center h-40"><img src="{{ asset('images/we_buy_your_bag/Chanel.svg') }}" alt="Luxury Chanel Bag" class="mx-auto my-10 w-2/5"></div>
        </div>
    </div>
    <div class="bg-[#C8928A] text-white py-12 pb-14 text-center">
        <h2 class="text-center text-4xl font-regular">ES MUY SENCILLO</h2>
        <div class="grid grid-cols-3 gap-8 max-w-7xl mx-auto">
            <div class="text-center">
                <div class="flex">
                    <span class="text-[245px] text-[#482626]">1</span>
                    <h3 class="text-robotoCondensed font-regular text-4xl w-1/2 text-left mt-32">Busca ese bolso que ya no usas</h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular relative top-[-60px]">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras dignissim, lectus ut bibendum dignissim, erat lorem scelerisque mauris, eu ultricies nibh dolor eget libero. In sagittis lorem sem, a sollicitudin mauris laoreet eu. Suspendisse efficitur nisl sagittis, vehicula eros non, scelerisque orci. Suspendisse scelerisque et tellus non rutrum.</p>
            </div>
            <div class="text-center">
                <div class="flex">
                    <span class="text-[245px] text-[#482626]">2</span>
                    <h3 class="text-robotoCondensed font-regular text-4xl w-1/2 text-left mt-32">Envíanos fotos y toda su historia</h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular relative top-[-60px]">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras dignissim, lectus ut bibendum dignissim, erat lorem scelerisque mauris, eu ultricies nibh dolor eget libero. In sagittis lorem sem, a sollicitudin mauris laoreet eu. Suspendisse efficitur nisl sagittis, vehicula eros non, scelerisque orci. Suspendisse scelerisque et tellus non rutrum.</p>
            </div>
            <div class="text-center">
                <div class="flex">
                    <span class="text-[245px] text-[#482626]">3</span>
                    <h3 class="text-robotoCondensed font-regular text-4xl w-1/2 text-left mt-32">¡Te compramos tu bolso!</h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular relative top-[-60px]">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras dignissim, lectus ut bibendum dignissim, erat lorem scelerisque mauris, eu ultricies nibh dolor eget libero. In sagittis lorem sem, a sollicitudin mauris laoreet eu. Suspendisse efficitur nisl sagittis, vehicula eros non, scelerisque orci. Suspendisse scelerisque et tellus non rutrum.</p>
            </div>
        </div>
        <button class="mt-8 bg-black text-white px-12 py-3 rounded-full font-medium mx-auto">Ir al formulario &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;></button>
        
    </div>
        <!-- FAQs Section -->
        <div class="bg-[#3A1515] text-white py-12">
        <h2 class="text-center text-4xl font-regular mb-8">FAQs</h2>
        <div class="max-w-7xl mx-auto space-y-4 pb-10">
            <div class="grid grid-cols-2 gap-6 gap-x-32 max-w-8xl mx-auto mb-4 mt-12 w-full">
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div>
                    <details class="pb-6 border-b border-white">
                        <summary class="cursor-pointer">FAQ Question 1</summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
            </div>
            
            <!-- Repeat for other FAQs -->
        </div>
    </div>
</div>
@endsection

@metadata

<?php
seo()
->title(env('APP_NAME'), 'Tu lonja online para comprar pescado y marisco fresco')
->description('Encuentra pescados y mariscos frescos en nuestra tienda online. Del mar a tu casa en menos de 24 horas.')
->images(
    env('APP_LOGO_1_PATH'),
        env('APP_LOGO_2_PATH'),
);
?>