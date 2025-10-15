<div class="bg-background-color-4">
    @if(!$authorized)
        <!-- Unauthorized Access Message -->
        <div class="container mx-auto px-4 lg:px-6 pt-16 pb-8 max-w-screen-2xl">
            <div class="text-center">
                <div>
                    <svg class="w-20 h-20 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    
                    <h1 class="text-3xl font-light text-gray-800 mb-4 font-['Lovera']" style="color: #482626;">
                        {{ trans('components/checkout.not-authorized') }}
                    </h1>
                    
                    <p class="text-base text-gray-600 mb-6 font-robotoCondensed leading-relaxed max-w-md sm:max-w-lg lg:max-w-xl mx-auto">
                        {{ trans('components/checkout.not-authorized-message') }}
                    </p>
                    
                    <div class="space-y-3 max-w-md mx-auto">
                        <a href="{{ app()->getLocale() === 'es' ? url('/es/tienda') : url('/en/shop') }}" 
                           class="block w-full bg-[#CA2530] text-white py-3 px-6 rounded-lg font-medium hover:bg-[#A01E28] transition-colors text-center">
                            {{ trans('components/checkout.return-to-shop') }}
                        </a>
                        
                        <a href="mailto:info@bagsandtea.com" 
                           class="block w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center">
                            {{ app()->getLocale() === 'es' ? 'Contactar Soporte' : 'Contact Support' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif($orderFound && $order)
        <div class="container mx-auto px-4 lg:px-6 py-8 max-w-screen-2xl">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-4 font-['Lovera']" style="color: #482626;">
                    {{ trans('components/checkout.thank-you') }}
                </h1>
                <p class="text-lg text-gray-600 mb-6 font-robotoCondensed">
                    {{ trans('components/checkout.order-confirmation') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <!-- Order Details -->
                <div class="bg-white shadow-sm border p-8 rounded-lg">
                    <h2 class="text-xl font-medium text-color-2 mb-6 font-robotoCondensed">{{ trans('components/checkout.order-summary') }}</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-color-2 font-medium font-robotoCondensed">{{ trans('components/checkout.order-number') }}</span>
                            <span class="text-[#CA2530] font-bold font-robotoCondensed">{{ $order['order_number'] }}</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-color-2 font-robotoCondensed">{{ trans('components/checkout.order-total') }}</span>
                            <span class="text-lg font-bold text-[#CA2530] font-robotoCondensed">{{ number_format($order['total_amount'], 2, ',', '.') }}€</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-color-2 font-robotoCondensed">{{ trans('components/checkout.payment-method-used') }}</span>
                            <span class="text-color-2 font-robotoCondensed">
                                @switch($order['payment_method'] ?? 'pending')
                                    @case('stripe_card')
                                        {{ trans('components/checkout.credit-debit-card') }}
                                        @break
                                    @case('stripe_paypal')
                                        PayPal
                                        @break
                                    @case('stripe_klarna')
                                        Klarna
                                        @break
                                    @default
                                        {{ trans('components/checkout.pending') }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="py-2">
                            <span class="text-color-2 font-medium font-robotoCondensed">{{ trans('components/checkout.estimated-delivery') }}</span>
                            <p class="text-sm text-gray-600 mt-1 font-robotoCondensed">3-7 {{ trans('components/checkout.business-days') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white shadow-sm border p-8 rounded-lg">
                    <h3 class="text-xl font-medium text-color-2 mb-6 font-robotoCondensed">{{ trans('components/checkout.shipping-address-label') }}</h3>
                    
                    @if(isset($order['shipping_address']))
                        <div class="text-color-2 space-y-1">
                            <p class="font-medium font-robotoCondensed">
                                {{ $order['shipping_address']['first_name'] }} {{ $order['shipping_address']['last_name'] }}
                            </p>
                            <p class="font-robotoCondensed">{{ $order['shipping_address']['line1'] }}</p>
                            @if(!empty($order['shipping_address']['line2']))
                                <p class="font-robotoCondensed">{{ $order['shipping_address']['line2'] }}</p>
                            @endif
                            <p class="font-robotoCondensed">{{ $order['shipping_address']['postal_code'] }} {{ $order['shipping_address']['city'] }}</p>
                            @if(!empty($order['shipping_address']['state']))
                                <p class="font-robotoCondensed">{{ $order['shipping_address']['state'] }}</p>
                            @endif
                            <p class="font-robotoCondensed">
                                @php
                                    $translationKey = 'components/checkout.countries.' . $order['shipping_address']['country'];
                                    $countryName = trans($translationKey);
                                    echo $translationKey !== $countryName ? $countryName : $order['shipping_address']['country'];
                                @endphp
                            </p>
                        </div>
                    @endif
                </div>
                
                <!-- Billing Address -->
                <div class="bg-white shadow-sm border p-8 rounded-lg">
                    <h3 class="text-xl font-medium text-color-2 mb-6 font-robotoCondensed">{{ trans('components/checkout.billing-address') }}</h3>
                    
                    @if(isset($order['billing_address']))
                        <div class="text-color-2 space-y-1">
                            <p class="font-medium font-robotoCondensed">
                                {{ $order['billing_address']['first_name'] }} {{ $order['billing_address']['last_name'] }}
                            </p>
                            @if(!empty($order['billing_address']['vat_id']))
                                <p class="font-robotoCondensed">
                                    {{ trans('components/checkout.vat-id-label') }}: {{ $order['billing_address']['vat_id'] }}
                                </p>
                            @endif
                            <p class="font-robotoCondensed">{{ $order['billing_address']['line1'] }}</p>
                            @if(!empty($order['billing_address']['line2']))
                                <p class="font-robotoCondensed">{{ $order['billing_address']['line2'] }}</p>
                            @endif
                            <p class="font-robotoCondensed">{{ $order['billing_address']['postal_code'] }} {{ $order['billing_address']['city'] }}</p>
                            @if(!empty($order['billing_address']['state']))
                                <p class="font-robotoCondensed">{{ $order['billing_address']['state'] }}</p>
                            @endif
                            <p class="font-robotoCondensed">
                                @php
                                    $translationKey = 'components/checkout.countries.' . $order['billing_address']['country'];
                                    $countryName = trans($translationKey);
                                    echo $translationKey !== $countryName ? $countryName : $order['billing_address']['country'];
                                @endphp
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            @if(isset($order['suborders']) && count($order['suborders']) > 0)
                <div class="mt-6 max-w-6xl mx-auto">
                    <div class="bg-white shadow-sm border p-8 rounded-lg">
                        <h3 class="text-xl font-medium text-color-2 mb-6 font-robotoCondensed">{{ trans('components/checkout.order-items') }}</h3>
                        
                        <div class="space-y-4">
                            @foreach($order['suborders'] as $suborder)
                                @if(isset($suborder['order_items']))
                                    @foreach($suborder['order_items'] as $item)
                                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200 last:border-b-0">
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                                @if(isset($item['product_snapshot']['primary_image']['file_path']))
                                                    <img src="{{ asset($item['product_snapshot']['primary_image']['file_path']) }}" 
                                                         alt="{{ $item['product_name'][app()->getLocale()] ?? $item['product_name']['en'] ?? 'Product' }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                @php
                                                    $productName = $item['product_name'][app()->getLocale()] ?? $item['product_name']['en'] ?? 'Product';
                                                    $productSlug = null;

                                                    // Try to get slug from product_snapshot
                                                    if (isset($item['product_snapshot']['slug'])) {
                                                        if (is_array($item['product_snapshot']['slug'])) {
                                                            $productSlug = $item['product_snapshot']['slug'][app()->getLocale()] ?? $item['product_snapshot']['slug']['en'] ?? null;
                                                        } else {
                                                            $productSlug = $item['product_snapshot']['slug'];
                                                        }
                                                    }
                                                @endphp

                                                <h4 class="font-medium text-color-2 font-robotoCondensed">
                                                    @if($productSlug)
                                                        <a href="{{ route(app()->getLocale() === 'es' ? 'product.show.es' : 'product.show.en', ['locale' => app()->getLocale(), 'productSlug' => $productSlug]) }}"
                                                           class="hover:text-[#CA2530] transition-colors duration-200">
                                                            {{ $productName }}
                                                        </a>
                                                    @else
                                                        {{ $productName }}
                                                    @endif
                                                </h4>
                                                @if(!empty($item['product_sku']))
                                                    <p class="text-sm text-gray-600 font-robotoCondensed">SKU: {{ $item['product_sku'] }}</p>
                                                @endif
                                                <p class="text-sm text-gray-600 font-robotoCondensed">{{ trans('components/checkout.quantity') }}: {{ $item['quantity'] }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-medium text-[#CA2530] font-robotoCondensed">
                                                    {{ number_format($item['total_price'], 2, ',', '.') }}€
                                                </div>
                                                @if($item['quantity'] > 1)
                                                    <div class="text-sm text-gray-600 font-robotoCondensed">
                                                        {{ number_format($item['unit_price'], 2, ',', '.') }}€ {{ trans('components/cart.each') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Order Pricing Breakdown -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-color-2 font-bold font-robotoCondensed">{{ trans('components/checkout.subtotal') }}</span>
                                    <span class="text-color-2 font-robotoCondensed">{{ number_format($order['subtotal'] ?? 0, 2, ',', '.') }}€</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-color-2 font-robotoCondensed">
                                        {{ trans('components/checkout.shipping') }}
                                        @if(isset($order['shipping_address']['country']))
                                            @php
                                                $translationKey = 'components/checkout.countries.' . $order['shipping_address']['country'];
                                                $countryName = trans($translationKey);
                                                if ($translationKey === $countryName) {
                                                    $countryName = $order['shipping_address']['country'];
                                                }
                                                
                                                // Default delivery days for Spain and other countries
                                                $deliveryDaysMin = $order['shipping_address']['country'] === 'ES' ? 3 : 7;
                                                $deliveryDaysMax = $order['shipping_address']['country'] === 'ES' ? 7 : 10;
                                            @endphp
                                            ({{ $countryName }}) ({{ $deliveryDaysMin }}-{{ $deliveryDaysMax }} {{ trans('components/cart.delivery-days') }})
                                        @endif
                                    </span>
                                    <span class="text-color-2 font-robotoCondensed">{{ number_format($order['shipping_amount'] ?? 0, 2, ',', '.') }}€</span>
                                </div>
                                
                                @if(isset($order['order_fees']) && count($order['order_fees']) > 0)
                                    @foreach($order['order_fees'] as $fee)
                                        @if($fee['visible_to_customer'])
                                            <div class="flex justify-between">
                                                <span class="text-color-2 font-robotoCondensed">{{ $fee['fee_name'] }}</span>
                                                <span class="text-color-2 font-robotoCondensed">{{ number_format($fee['fee_amount'], 2, ',', '.') }}€</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                
                                <div class="flex justify-between pt-2 border-t border-gray-200">
                                    <span class="text-lg font-bold text-color-2 font-robotoCondensed">{{ trans('components/checkout.total') }} <span class="text-sm text-gray-600 font-normal font-robotoCondensed">{{ trans('components/cart.vat-included') }}</span></span>
                                    <span class="text-lg font-bold text-[#CA2530] font-robotoCondensed">{{ number_format($order['total_amount'], 2, ',', '.') }}€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Continue Shopping -->
            <div class="text-center mt-8">
                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" 
                   class="inline-block bg-[#CA2530] hover:bg-[#A01E28] text-white py-3 px-8 font-medium font-robotoCondensed transition-colors duration-200">
                    {{ trans('components/checkout.continue-shopping') }}
                </a>
            </div>

        </div>
    @else
        <div class="container mx-auto px-4 lg:px-6 py-8 max-w-screen-2xl">
            <!-- Order Not Found -->
            <div class="text-center">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-4 font-['Lovera']" style="color: #482626;">
                    {{ trans('components/checkout.order-not-found') }}
                </h1>
                <p class="text-lg text-gray-600 mb-6 font-robotoCondensed">
                    {{ trans('components/checkout.order-not-found-message') }}
                </p>
                <a href="{{ route(app()->getLocale() === 'es' ? 'shop.show.es' : 'shop.show.en', ['locale' => app()->getLocale()]) }}" 
                   class="inline-block bg-[#CA2530] hover:bg-[#A01E28] text-white py-3 px-8 font-medium font-robotoCondensed transition-colors duration-200">
                    {{ trans('components/checkout.continue-shopping') }}
                </a>
            </div>
        @endif
    </div>
</div>