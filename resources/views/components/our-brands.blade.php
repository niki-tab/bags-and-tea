@php
        //$stringBrandsTitle = trans('pages/we-buy-your-bag.brands_title');
        $stringBrandsTitle = $title;
@endphp
<div class="py-8 md:py-16 bg-[#F8F3F0]">
    <h2 class="text-center text-2xl md:text-4xl text-[#482626] mb-8 md:mb-14 font-['Lovera'] mx-4 md:mx-0">{{ $stringBrandsTitle }}</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-7xl mx-auto mb-4 px-4 md:px-4 lg:px-4 xl:px-0">
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-hermes' : '/en/shop/hermes-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Hermes.svg') }}" alt="Luxury Hermes Bag" 
                class="w-2/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-yves-saint-laurent' : '/en/shop/yves-saint-laurent-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/yves-saint-laurent.svg') }}" alt="Luxury YSL Bag" 
                class="w-3/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-christian-dior' : '/en/shop/christian-dior-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Dior.svg') }}" alt="Luxury Dior Bag" 
                class="w-2/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-louis-vuitton' : '/en/shop/louis-vuitton-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Louis-Vuitton.svg') }}" alt="Luxury Louis Vuitton Bag" 
                class="w-3/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-gucci' : '/en/shop/gucci-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Gucci.svg') }}" alt="Luxury Gucci Bag" 
                class="w-3/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-prada' : '/en/shop/prada-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Prada.svg') }}" alt="Luxury Prada Bag" 
                class="w-3/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-goyard' : '/en/shop/goyard-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Goyard.svg') }}" alt="Luxury Goyard Bag" 
                class="w-3/5">
        </a>
        <a href="{{ app()->getLocale() == 'es' ? '/es/tienda/bolsos-chanel' : '/en/shop/chanel-bags' }}" class="bg-[#E3D4CB] text-center h-32 md:h-40 flex items-center justify-center hover:bg-[#D4C5BC] transition-colors cursor-pointer">
            <img src="{{ asset('images/we_buy_your_bag/Chanel.svg') }}" alt="Luxury Chanel Bag" 
                class="w-2/5">
        </a>
    </div>
</div>