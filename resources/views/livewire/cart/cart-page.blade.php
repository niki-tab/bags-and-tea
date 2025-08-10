<div class="bg-background-color-4 {{ count($cartItems) > 0 ? 'min-h-screen' : '' }}">
    <div class="container mx-auto px-4 lg:px-8 {{ count($cartItems) > 0 ? 'py-14' : 'py-8' }} max-w-screen-2xl">
        <!-- Cart Title -->
        <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-8 text-center font-['Lovera']" style="color: #482626;">
            {{ trans('components/cart.title') }}
        </h1>

        <!-- Loading State -->
        @if($isLoading)
            <div class="text-center py-8">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-color-2 font-robotoCondensed">{{ trans('components/cart.loading') }}</span>
                </div>
            </div>
        @endif

        <!-- Success/Error Messages -->
        @if(session('cart-success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center">
                {{ session('cart-success') }}
            </div>
        @endif

        @if(session('cart-error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-center">
                {{ session('cart-error') }}
            </div>
        @endif

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items (2/3 width) -->
                <div class="lg:col-span-2">
                    <!-- Clear Cart Button -->
                    <div class="mb-6 text-right">
                        <button wire:click="clearCart" 
                                class="text-red-600 hover:text-red-800 font-medium text-sm"
                                onclick="return confirm('{{ trans('components/cart.confirm-clear') }}')">
                            {{ trans('components/cart.clear-cart') }}
                        </button>
                    </div>

                    <!-- Cart Items List -->
                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                            @if(isset($item['product']))
                                <div class="bg-white shadow-sm border p-6">
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0">
                                            @if(isset($item['product']['primary_image']) && !empty($item['product']['primary_image']['file_path']))
                                                <img src="{{ str_starts_with($item['product']['primary_image']['file_path'], 'https://') || str_contains($item['product']['primary_image']['file_path'], 'r2.cloudflarestorage.com') ? $item['product']['primary_image']['file_path'] : asset($item['product']['primary_image']['file_path']) }}" 
                                                     alt="{{ $item['product']['name'][app()->getLocale()] ?? 'Product' }}" 
                                                     class="w-24 h-24 object-cover rounded-lg">
                                            @else
                                                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-grow">
                                            <h3 class="text-lg font-medium text-color-2 mb-2">
                                                {{ $item['product']['name'][app()->getLocale()] ?? 'Product' }}
                                            </h3>
                                            
                                            @if(isset($item['product']['brand']['name']))
                                                <p class="text-sm text-gray-600 mb-2">
                                                    {{ $item['product']['brand']['name'][app()->getLocale()] ?? $item['product']['brand']['name'] }}
                                                </p>
                                            @endif

                                            <div class="flex items-center justify-between">
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm text-color-2">{{ trans('components/cart.quantity') }}:</span>
                                                    <button wire:click="updateQuantity('{{ $item['product_id'] }}', {{ max(1, $item['quantity'] - 1) }})" 
                                                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 {{ $item['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}">
                                                        <span class="text-sm font-medium">-</span>
                                                    </button>
                                                    <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                                                    <button wire:click="updateQuantity('{{ $item['product_id'] }}', {{ min(999, $item['quantity'] + 1) }})" 
                                                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-100 {{ $item['quantity'] >= ($item['product']['stock'] ?? 0) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $item['quantity'] >= ($item['product']['stock'] ?? 0) ? 'disabled' : '' }}>
                                                        <span class="text-sm font-medium">+</span>
                                                    </button>
                                                </div>

                                                <!-- Price -->
                                                <div class="text-right">
                                                    <div class="text-lg font-bold text-[#CA2530]">
                                                        {{ number_format(($item['product']['price'] ?? 0) * $item['quantity'], 2, ',', '.') }}€
                                                        <span class="text-xs font-normal align-baseline">{{ trans('components/cart.vat-included') }}</span>
                                                    </div>
                                                    @if($item['quantity'] > 1)
                                                        <div class="text-sm text-gray-500">
                                                            {{ number_format($item['product']['price'] ?? 0, 2, ',', '.') }}€ {{ trans('components/cart.each') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="mt-4 text-right">
                                                <button wire:click="removeItem('{{ $item['product_id'] }}')" 
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                        onclick="return confirm('{{ trans('components/cart.confirm-remove') }}')">
                                                    {{ trans('components/cart.remove') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Cart Summary (1/3 width) -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm border p-6 sticky top-4">
                        <h2 class="text-xl font-medium text-color-2 mb-6">{{ trans('components/cart.summary') }}</h2>
                        
                        <div class="space-y-4">
                            <!-- Cart Items -->
                            @foreach($cartItems as $item)
                                @if(isset($item['product']))
                                    <div class="flex justify-between">
                                        <span class="text-color-2">{{ $item['product']['name'][app()->getLocale()] ?? 'Product' }} ({{ $item['quantity'] }})</span>
                                        <span class="font-medium text-color-2">{{ number_format(($item['product']['price'] ?? 0) * $item['quantity'], 2, ',', '.') }}€</span>
                                    </div>
                                @endif
                            @endforeach
                            
                            <!-- Subtotal -->
                            @if(count($cartItems) > 0)
                                <hr class="border-gray-200">
                                <div class="flex justify-between font-bold">
                                    <div class="text-color-2">
                                        <span>{{ trans('components/cart.subtotal') }}</span>
                                        <span class="text-xs font-normal align-baseline">{{ trans('components/cart.vat-included') }}</span>
                                    </div>
                                    <span class="text-color-2">{{ number_format($subtotal, 2, ',', '.') }}€</span>
                                </div>
                                
                                <!-- Marketplace Fees -->
                                @if(!empty($fees))
                                    @foreach($fees as $fee)
                                        <div class="flex justify-between">
                                            <span class="text-color-2">{{ $fee['name'] }}</span>
                                            <span class="font-medium text-color-2">{{ number_format($fee['amount'], 2, ',', '.') }}€</span>
                                        </div>
                                    @endforeach
                                @endif
                                
                                <!-- Shipping -->
                                @if($shipping)
                                    <div class="flex justify-between">
                                        <span class="text-color-2">
                                            {{ trans('components/cart.shipping') }}
                                            @if(!($shipping['is_default'] ?? false))
                                                ({{ $shipping['zone_name'] }})
                                                @if($shipping['delivery_days_min'] && $shipping['delivery_days_max'])
                                                    <span class="text-xs text-gray-500">
                                                        ({{ $shipping['delivery_days_min'] }}-{{ $shipping['delivery_days_max'] }} {{ trans('components/cart.delivery-days') }})
                                                    </span>
                                                @endif
                                            @endif
                                        </span>
                                        <span class="font-medium text-color-2">
                                            @if($shipping['is_default'] ?? false)
                                                {{ trans('components/cart.shipping-calculated') }}
                                            @else
                                                {{ number_format($shipping['amount'], 2, ',', '.') }}€
                                            @endif
                                        </span>
                                    </div>
                                    @if($shipping['is_default'] ?? false)
                                        <div class="text-xs text-gray-500 pl-0">
                                            {{ trans('components/cart.shipping-calculated') }}
                                        </div>
                                    @endif
                                @endif
                                
                                <hr class="border-gray-200">
                                
                                <!-- Total -->
                                <div class="flex justify-between text-lg font-bold">
                                    <div class="text-color-2">
                                        <span>{{ trans('components/cart.total') }}</span>
                                        <span class="text-xs font-normal align-baseline">{{ trans('components/cart.vat-included') }}</span>
                                    </div>
                                    <span class="text-[#CA2530]">{{ number_format($totalPrice, 2, ',', '.') }}€</span>
                                </div>
                            @endif
                        </div>

                        <!-- Checkout Button -->
                        <div class="mt-8">
                            <a href="{{ route(app()->getLocale() === 'es' ? 'checkout.show.es' : 'checkout.show.en', ['locale' => app()->getLocale()]) }}" 
                               class="block w-full bg-[#CA2530] hover:bg-[#A01E28] text-white py-4 px-6 text-lg font-medium text-center font-['Lora'] transition-colors duration-200">
                                {{ trans('components/cart.proceed-to-checkout') }}
                            </a>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="mt-4 text-center">
                            <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" 
                               class="text-[#CA2530] hover:text-[#A01E28] font-medium">
                                {{ trans('components/cart.continue-shopping') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-8">
                <div class="mb-8">
                    <img src="{{ asset('images/icons/icon_cart_header_hover.svg') }}" 
                         alt="{{ trans('components/cart.empty-cart') }}" 
                         class="w-24 h-24 mx-auto"
                         style="filter: brightness(0) saturate(100%) invert(23%) sepia(98%) saturate(2074%) hue-rotate(353deg) brightness(83%) contrast(94%);">
                </div>
                <h2 class="text-xl font-medium text-color-2 mb-4">{{ trans('components/cart.empty') }}</h2>
                <p class="text-gray-600 mb-8">{{ trans('components/cart.empty-description') }}</p>
                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" 
                   class="inline-block bg-[#CA2530] hover:bg-[#A01E28] text-white py-3 px-8 font-medium font-['Lora'] transition-colors duration-200">
                    {{ trans('components/cart.start-shopping') }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check localStorage for consent settings
    const consentData = localStorage.getItem('cookiePreferences');
    
    if (consentData) {
        try {
            const consent = JSON.parse(consentData);
            const analyticsConsent = consent.analytics === true;
            
            console.log('Found cookiePreferences:', consent);
            console.log('Analytics consent:', analyticsConsent);
            
            // Update the Livewire component with analytics consent
            @this.call('updateAnalyticsConsent', analyticsConsent);
        } catch (e) {
            console.warn('Could not parse consent data from localStorage:', e);
        }
    } else {
        console.log('No cookiePreferences found in localStorage');
    }
    
    // Listen for storage changes (if consent is updated in another tab)
    window.addEventListener('storage', function(e) {
        if (e.key === 'cookiePreferences') {
            if (e.newValue) {
                try {
                    const consent = JSON.parse(e.newValue);
                    const analyticsConsent = consent.analytics === true;
                    console.log('Updated cookiePreferences:', consent);
                    @this.call('updateAnalyticsConsent', analyticsConsent);
                } catch (err) {
                    console.warn('Could not parse updated consent data:', err);
                }
            }
        }
    });
});
</script>