{{-- Add to Cart Button - Simplified for single luxury items --}}
<div class="space-y-4">
    {{-- Add to Cart Button - Same design as PDP but functional --}}
    <button wire:click="addToCart" 
            class="w-full {{ $isLoading || $isDisabled ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-color-2 hover:bg-[#A01E28]' }} text-white py-4 px-6 text-lg font-medium font-['Lora'] transition-colors duration-200"
            {{ $isLoading || $isDisabled ? 'disabled' : '' }}>
        @if($isLoading)
            <div class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ trans('components/cart.adding') }}
            </div>
        @elseif($isDisabled)
            {{ $this->getDisabledMessage() }}
        @else
            {{ $buttonText }}
        @endif
    </button>

    {{-- Buy Now Button - Add to cart and redirect to cart --}}
    <button wire:click="addToCartAndGoToCart"
            class="w-full {{ $isLoading || $isDisabled ? 'bg-gray-400 cursor-not-allowed opacity-60' : 'bg-white border-2 border-[#CA2530] text-[#CA2530] hover:bg-[#CA2530] hover:text-white' }} py-4 px-6 text-lg font-medium font-['Lora'] transition-colors duration-200"
            {{ $isLoading || $isDisabled ? 'disabled' : '' }}>
        @if($isLoading)
            <div class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ trans('components/cart.adding') }}
            </div>
        @elseif($isDisabled)
            {{ $this->getDisabledMessage() }}
        @else
            {{ app()->getLocale() === 'es' ? 'Realizar Compra' : 'Buy Now' }}
        @endif
    </button>

    {{-- WhatsApp Button - Hidden when sold out --}}
    @if(!$isSoldOut && !$isHidden)
        @php
            $whatsappMessage = app()->getLocale() === 'es'
                ? 'Hola, me gustaría más información sobre: ' . ($productName ?? 'este producto') . "\n" . url()->current()
                : 'Hi, I would like more information about: ' . ($productName ?? 'this product') . "\n" . url()->current();
        @endphp
        <a href="https://wa.me/31617416954?text={{ urlencode($whatsappMessage) }}"
           target="_blank"
           class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#128C7E] text-white py-4 px-6 text-lg font-medium font-['Lora'] transition-colors duration-200">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            {{ app()->getLocale() === 'es' ? 'Consulta por WhatsApp' : 'WhatsApp Us' }}
        </a>
    @endif

    {{-- Success/Error Messages --}}
    @if(session('cart-success'))
        <div class="text-green-600 text-center font-medium">
            {{ session('cart-success') }}
        </div>
    @endif

    @if(session('cart-error'))
        <div class="text-red-600 text-center font-medium">
            {{ session('cart-error') }}
        </div>
    @endif
</div>