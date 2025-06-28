@php
        //$stringBrandsTitle = trans('pages/we-buy-your-bag.brands_title');
        $stringBrandsTitle = $title;
@endphp
<div class="py-8 md:py-16 bg-[#F8F3F0]">
    <h2 class="text-center text-2xl md:text-4xl text-[#482626] mb-8 md:mb-14 font-['Lovera']">{{ $stringBrandsTitle }}</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-7xl mx-auto mb-4 px-4 md:px-4 lg:px-4 xl:px-0">
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Hermes.svg') }}" alt="Luxury Hermes Bag" 
                class="w-2/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/yves-saint-laurent.svg') }}" alt="Luxury YSL Bag" 
                class="w-3/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Dior.svg') }}" alt="Luxury Dior Bag" 
                class="w-2/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Louis-Vuitton.svg') }}" alt="Luxury Louis Vuitton Bag" 
                class="w-3/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Gucci.svg') }}" alt="Luxury Gucci Bag" 
                class="w-3/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Prada.svg') }}" alt="Luxury Prada Bag" 
                class="w-3/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Goyard.svg') }}" alt="Luxury Goyard Bag" 
                class="w-3/5">
        </div>
        <div class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center">
            <img src="{{ asset('images/we_buy_your_bag/Chanel.svg') }}" alt="Luxury Chanel Bag" 
                class="w-2/5">
        </div>
    </div>
</div>