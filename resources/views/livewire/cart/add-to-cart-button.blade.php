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