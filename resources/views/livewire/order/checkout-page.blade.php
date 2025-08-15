<div class="bg-background-color-4 min-h-screen">
    <div class="container mx-auto px-4 lg:px-8 py-8 max-w-screen-2xl">
        <!-- Page Title -->
        <h1 class="text-3xl lg:text-4xl font-light text-gray-800 mb-8 text-center font-['Lovera']" style="color: #482626;">
            {{ trans('components/checkout.title') }}
        </h1>

        <!-- Loading State -->
        @if($isLoading)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-8 rounded-lg text-center">
                    <div class="inline-flex items-center mb-4">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-color-2 font-robotoCondensed">{{ trans('components/checkout.processing') }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ trans('components/checkout.please-wait') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('checkout-error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-center">
                {{ session('checkout-error') }}
            </div>
        @endif

        <!-- Validation Errors Summary -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="font-medium mb-2">{{ trans('components/checkout.please-fix-errors') }}:</div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Single-Page Checkout Layout Info -->
        <div class="mb-8 text-center">
            <p class="text-gray-600 text-sm">{{ trans('components/checkout.single-page-info') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section 1: Customer Information -->
                <div class="bg-white shadow-sm border p-6">
                    <h2 class="text-xl font-medium text-color-2 mb-6 flex items-center">
                        <span class="bg-[#CA2530] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium mr-3">1</span>
                        {{ trans('components/checkout.customer-information') }}
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.email') }} *
                            </label>
                            <input type="email" wire:model="customer_email" id="customer_email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.full-name') }} *
                            </label>
                            <input type="text" wire:model="customer_name" id="customer_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.phone') }}
                            </label>
                            <input type="tel" wire:model="customer_phone" id="customer_phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('customer_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Shipping Address -->
                <div class="bg-white shadow-sm border p-6">
                    <h2 class="text-xl font-medium text-color-2 mb-6 flex items-center">
                        <span class="bg-[#CA2530] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium mr-3">2</span>
                        {{ trans('components/checkout.shipping-address') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label for="shipping_first_name" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.first-name') }} *
                            </label>
                            <input type="text" wire:model="shipping_first_name" id="shipping_first_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('shipping_first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Last Name -->
                        <div>
                            <label for="shipping_last_name" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.last-name') }} *
                            </label>
                            <input type="text" wire:model="shipping_last_name" id="shipping_last_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('shipping_last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="shipping_line1" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.address-line-1') }} *
                            </label>
                            <input type="text" wire:model="shipping_line1" id="shipping_line1" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('shipping_line1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="shipping_line2" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.address-line-2') }}
                            </label>
                            <input type="text" wire:model="shipping_line2" id="shipping_line2" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                        </div>

                        <div>
                            <label for="shipping_city" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.city') }} *
                            </label>
                            <input type="text" wire:model="shipping_city" id="shipping_city" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('shipping_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="shipping_postal_code" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.postal-code') }} *
                            </label>
                            <input type="text" wire:model="shipping_postal_code" id="shipping_postal_code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            @error('shipping_postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="shipping_country" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.country') }} *
                            </label>
                            <select wire:model.live="shipping_country" id="shipping_country" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                <option value="">{{ trans('components/checkout.select-country') }}</option>
                                @foreach($availableCountries as $countryCode => $countryName)
                                    <option value="{{ $countryCode }}">{{ $countryName }}</option>
                                @endforeach
                            </select>
                            @error('shipping_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="shipping_state" class="block text-sm font-medium text-color-2 mb-1">
                                {{ trans('components/checkout.state-province') }}
                            </label>
                            <input type="text" wire:model="shipping_state" id="shipping_state" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Billing Address -->
                <div class="bg-white shadow-sm border p-6">
                    <h2 class="text-xl font-medium text-color-2 mb-6 flex items-center">
                        <span class="bg-[#CA2530] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium mr-3">3</span>
                        {{ trans('components/checkout.billing-address') }}
                    </h2>
                    
                    <!-- Same as Shipping Checkbox -->
                    <div class="mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="same_as_billing" wire:change="$refresh" class="ml-4 rounded border-gray-300 text-[#CA2530] focus:ring-[#CA2530]">
                            <span class="ml-2 text-sm text-color-2">{{ trans('components/checkout.billing-same-as-shipping') }}</span>
                        </label>
                    </div>

                    <!-- Billing Address Fields -->
                    @if(!$same_as_billing)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="billing_first_name" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.first-name') }} *
                                </label>
                                <input type="text" wire:model="billing_first_name" id="billing_first_name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                @error('billing_first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Last Name -->
                            <div>
                                <label for="billing_last_name" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.last-name') }} *
                                </label>
                                <input type="text" wire:model="billing_last_name" id="billing_last_name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                @error('billing_last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- VAT ID -->
                            <div class="md:col-span-2">
                                <label for="billing_vat_id" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.vat-id') }}
                                </label>
                                <input type="text" wire:model="billing_vat_id" id="billing_vat_id" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent"
                                       placeholder="{{ trans('components/checkout.vat-id-placeholder') }}">
                                @error('billing_vat_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="billing_line1" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.address-line-1') }} *
                                </label>
                                <input type="text" wire:model="billing_line1" id="billing_line1" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                @error('billing_line1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="billing_line2" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.address-line-2') }}
                                </label>
                                <input type="text" wire:model="billing_line2" id="billing_line2" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            </div>

                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.city') }} *
                                </label>
                                <input type="text" wire:model="billing_city" id="billing_city" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                @error('billing_city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="billing_postal_code" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.postal-code') }} *
                                </label>
                                <input type="text" wire:model="billing_postal_code" id="billing_postal_code" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                @error('billing_postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="billing_country" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.country') }} *
                                </label>
                                <select wire:model="billing_country" id="billing_country" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                                    <option value="">{{ trans('components/checkout.select-country') }}</option>
                                    @foreach($availableCountries as $countryCode => $countryName)
                                        <option value="{{ $countryCode }}">{{ $countryName }}</option>
                                    @endforeach
                                </select>
                                @error('billing_country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="billing_state" class="block text-sm font-medium text-color-2 mb-1">
                                    {{ trans('components/checkout.state-province') }}
                                </label>
                                <input type="text" wire:model="billing_state" id="billing_state" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#CA2530] focus:border-transparent">
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Section 4: Payment Method -->
                <div class="bg-white shadow-sm border p-6">
                    <h2 class="text-xl font-medium text-color-2 mb-6 flex items-center">
                        <span class="bg-[#CA2530] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium mr-3">4</span>
                        {{ trans('components/checkout.payment-method') }}
                    </h2>
                    
                    @if(!$shippingAvailable)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <p class="text-red-700 font-medium">{{ trans('components/checkout.shipping-unavailable-payment') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="border rounded-lg p-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model="payment_method" value="stripe_card" class="text-[#CA2530] focus:ring-[#CA2530]">
                                    <span class="ml-3 flex items-center">
                                        <span class="text-sm font-medium text-color-2 mr-3">{{ trans('components/checkout.credit-debit-card') }}</span>
                                        <div class="flex space-x-2">
                                            <img src="{{ asset('images/checkout/visa.svg') }}" alt="Visa" class="h-6 w-auto">
                                            <img src="{{ asset('images/checkout/mastercard.svg') }}" alt="Mastercard" class="h-6 w-auto">
                                            <img src="{{ asset('images/checkout/american-express.svg') }}" alt="American Express" class="h-6 w-auto">
                                        </div>
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endif
                    
                    @error('payment_method') 
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div> 
                    @enderror

                    <!-- Place Order Button -->
                    <div class="mt-8">
                        @if(!$shippingAvailable)
                            <button disabled class="w-full bg-gray-400 text-white py-4 px-8 text-lg font-medium font-['Lora'] cursor-not-allowed">
                                {{ trans('components/checkout.shipping-not-available-button') }}
                            </button>
                        @else
                            <button wire:click="createPaymentIntentOnly" 
                                    class="w-full bg-[#CA2530] hover:bg-[#A01E28] text-white py-4 px-8 text-lg font-medium font-['Lora'] transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if($totalAmount <= 0) disabled @endif
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="createPaymentIntentOnly" class="font-robotoCondensed">
                                    {{ trans('components/checkout.select-payment-method') }}
                                </span>
                                <span wire:loading wire:target="createPaymentIntentOnly">
                                    <svg class="animate-spin inline-block mr-2 h-5 w-5 text-white align-middle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="align-middle font-robotoCondensed">{{ trans('components/checkout.processing-order') }}</span>
                                </span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Stripe Payment Form (will be shown dynamically) -->
                <div id="stripe-payment-section" class="bg-white shadow-sm border p-6" style="display: none;">
                    <h2 class="text-xl font-medium text-color-2 mb-6 flex items-center">
                        <span class="bg-[#CA2530] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-medium mr-3">5</span>
                        {{ trans('components/checkout.complete-payment') }}
                    </h2>
                    
                    <div id="payment-element" class="mb-4">
                        <!-- Stripe Elements will create form elements here -->
                    </div>
                    
                    @if(!$shippingAvailable)
                        <div class="w-full bg-red-100 border border-red-400 text-red-700 py-4 px-4 rounded mb-4 text-center">
                            {{ trans('components/checkout.shipping-unavailable-message') }}
                        </div>
                        <button disabled class="w-full bg-gray-400 text-white py-4 px-8 text-lg font-medium font-['Lora'] cursor-not-allowed">
                            {{ trans('components/checkout.shipping-not-available') }}
                        </button>
                    @else
                        <button id="submit-payment" class="w-full bg-[#CA2530] hover:bg-[#A01E28] text-white py-4 px-8 text-lg font-medium font-['Lora'] transition-colors duration-200">
                            <span id="submit-payment-text" class="font-robotoCondensed">
                                {{ trans('components/checkout.place-order') }} - {{ number_format($totalAmount, 2, ',', '.') }}€
                            </span>
                            <span id="submit-payment-loading" class="hidden">
                                <svg class="animate-spin inline-block mr-2 h-5 w-5 text-white align-middle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="align-middle font-robotoCondensed">{{ trans('components/checkout.processing-payment') }}</span>
                            </span>
                        </button>
                    @endif
                </div>

            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-sm border p-6 sticky top-8">
                    <h3 class="text-xl font-medium text-color-2 mb-6">{{ trans('components/checkout.order-summary') }}</h3>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-4">
                                @if(isset($item['product']['primary_image']) && !empty($item['product']['primary_image']['file_path']))
                                    <img src="{{ str_starts_with($item['product']['primary_image']['file_path'], 'https://') || str_contains($item['product']['primary_image']['file_path'], 'r2.cloudflarestorage.com') ? $item['product']['primary_image']['file_path'] : asset($item['product']['primary_image']['file_path']) }}" 
                                         alt="{{ $item['product']['name'][app()->getLocale()] ?? 'Product' }}" 
                                         class="w-24 h-24 object-cover rounded">
                                @else
                                        <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-color-2">
                                        @php
                                            $productName = $item['product']['name'] ?? 'Product Name';
                                            if (is_array($productName)) {
                                                $productName = $productName[app()->getLocale()] ?? $productName['en'] ?? 'Product Name';
                                            }
                                        @endphp
                                        {{ $productName }}
                                    </h4>
                                    <p class="text-sm text-gray-500">{{ trans('components/checkout.quantity') }}: {{ $item['quantity'] }}</p>
                                    <p class="text-sm font-medium text-color-2">
                                        {{ number_format(($item['product']['price'] ?? 0) * $item['quantity'], 2, ',', '.') }}€
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-sm font-medium text-color-2">
                            <span>{{ trans('components/checkout.subtotal') }}</span>
                            <span>{{ number_format($subtotal, 2, ',', '.') }}€</span>
                        </div>
                        
                        @if($totalFees > 0)
                            @foreach($fees as $fee)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $fee['name'] }}</span>
                                    <span class="text-color-2">{{ number_format($fee['amount'] ?? 0, 2, ',', '.') }}€</span>
                                </div>
                            @endforeach
                        @endif
                        
                        @if(!$shippingAvailable)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ trans('components/checkout.shipping') }}</span>
                                <span class="text-red-600 font-medium">{{ trans('components/checkout.shipping-not-available') }}</span>
                            </div>
                        @elseif($shippingCost > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    {{ trans('components/checkout.shipping') }}
                                    @if(!empty($shippingDetails) && !($shippingDetails['is_default'] ?? false))
                                        ({{ $shippingDetails['zone_name'] }})
                                        @if($shippingDetails['delivery_days_min'] && $shippingDetails['delivery_days_max'])
                                            ({{ $shippingDetails['delivery_days_min'] }}-{{ $shippingDetails['delivery_days_max'] }} {{ trans('components/cart.delivery-days') }})
                                        @endif
                                    @endif
                                </span>
                                <span class="text-color-2">{{ number_format($shippingCost, 2, ',', '.') }}€</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between text-lg font-medium text-color-2 border-t pt-2">
                            <span>{{ trans('components/checkout.total') }}</span>
                            <span>{{ number_format($totalAmount, 2, ',', '.') }}€</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('livewire:init', () => {
    console.log('Checkout page loaded, setting up Stripe integration');
    
    // Check payment section on page load
    setTimeout(() => {
        const section = document.getElementById('stripe-payment-section');
        console.log('Payment section check on load:', section ? 'FOUND' : 'NOT FOUND');
    }, 1000);
    
    let stripe;
    let elements;
    let paymentElement;
    let isStripeInitialized = false;
    
    // Check initial state
    console.log('Initial clientSecret:', '{{ $clientSecret ?? "NOT_SET" }}');
    console.log('Initial orderNumber:', '{{ $orderNumber ?? "NOT_SET" }}');
    
    function initializeStripe(clientSecret, orderNumber) {
        console.log('Initializing Stripe with:', { clientSecret: clientSecret?.substring(0, 20) + '...', orderNumber });
        
        if (typeof Stripe === 'undefined') {
            console.log('Stripe not loaded yet, retrying in 100ms');
            setTimeout(() => initializeStripe(clientSecret, orderNumber), 100);
            return;
        }
        
        // Allow re-initialization with new clientSecret
        if (isStripeInitialized) {
            console.log('Stripe already initialized, but allowing re-initialization with new clientSecret');
            // Reset the flag to allow re-initialization
            isStripeInitialized = false;
            
            // Clear any existing payment element
            const existingPaymentElement = document.getElementById('payment-element');
            if (existingPaymentElement) {
                existingPaymentElement.innerHTML = '';
                console.log('Cleared existing payment element');
            }
        }
        
        try {
            stripe = Stripe('{{ config('services.stripe.key') }}');
            console.log('Stripe instance created');
            
            elements = stripe.elements({
                clientSecret: clientSecret
            });
            console.log('Stripe elements created');
            
            paymentElement = elements.create('payment');
            console.log('Payment element created');
            
            // Show the stripe payment section
            const stripeSection = document.getElementById('stripe-payment-section');
            if (stripeSection) {
                stripeSection.style.display = 'block';
                console.log('Stripe payment section made visible');
                const computedStyles = window.getComputedStyle(stripeSection);
                const rect = stripeSection.getBoundingClientRect();
                
                console.log('Stripe section styles:', {
                    display: stripeSection.style.display,
                    visibility: computedStyles.visibility,
                    height: computedStyles.height,
                    opacity: computedStyles.opacity,
                    position: computedStyles.position,
                    zIndex: computedStyles.zIndex,
                    transform: computedStyles.transform,
                    overflow: computedStyles.overflow
                });
                
                console.log('Stripe section position:', {
                    top: rect.top,
                    left: rect.left,
                    right: rect.right,
                    bottom: rect.bottom,
                    width: rect.width,
                    height: rect.height,
                    windowHeight: window.innerHeight,
                    windowWidth: window.innerWidth,
                    scrollY: window.scrollY
                });
                
                // Apply normal styling to ensure visibility
                stripeSection.style.zIndex = '1';
                stripeSection.style.position = 'relative';
                console.log('Applied normal visibility styles');
                
                // Scroll to the payment section
                stripeSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                console.log('Scrolled to payment section');
            } else {
                console.error('stripe-payment-section element not found in DOM');
            }
            
            const paymentElementContainer = document.getElementById('payment-element');
            if (paymentElementContainer) {
                console.log('Payment element container found:', paymentElementContainer);
                console.log('Container innerHTML before mount:', paymentElementContainer.innerHTML);
                
                try {
                    paymentElement.mount('#payment-element');
                    console.log('✅ Payment element mount called successfully');
                    
                    // Check if mounting actually worked
                    setTimeout(() => {
                        console.log('Container innerHTML after mount:', paymentElementContainer.innerHTML);
                        const stripeFrames = paymentElementContainer.querySelectorAll('iframe');
                        console.log('Stripe iframes found:', stripeFrames.length);
                        if (stripeFrames.length === 0) {
                            console.error('❌ No Stripe iframes found - mounting failed');
                        } else {
                            console.log('✅ Stripe iframes created successfully');
                        }
                    }, 2000);
                    
                } catch (error) {
                    console.error('❌ Error mounting payment element:', error);
                }
                
                isStripeInitialized = true;
                
                // Add payment submission handler
                const submitButton = document.getElementById('submit-payment');
                if (submitButton) {
                    submitButton.addEventListener('click', async (e) => {
                        e.preventDefault();
                        console.log('Processing payment with orderNumber:', orderNumber);
                        
                        // Show loading state
                        const textSpan = document.getElementById('submit-payment-text');
                        const loadingSpan = document.getElementById('submit-payment-loading');
                        if (textSpan && loadingSpan) {
                            textSpan.classList.add('hidden');
                            loadingSpan.classList.remove('hidden');
                            submitButton.disabled = true;
                        }
                        
                        // Build the return URL properly
                        const baseUrl = '{{ url('/') }}';
                        const locale = '{{ app()->getLocale() }}';
                        const returnUrl = locale === 'es' 
                            ? baseUrl + '/es/pedido-confirmado/' + orderNumber
                            : baseUrl + '/en/order-confirmed/' + orderNumber;
                        
                        console.log('Return URL:', returnUrl);
                        
                        const {error, paymentIntent} = await stripe.confirmPayment({
                            elements,
                            redirect: 'if_required'
                        });
                        
                        if (error) {
                            console.error('Payment failed:', error);
                            alert(error.message);
                            
                            // Reset loading state on error
                            const textSpan = document.getElementById('submit-payment-text');
                            const loadingSpan = document.getElementById('submit-payment-loading');
                            if (textSpan && loadingSpan) {
                                textSpan.classList.remove('hidden');
                                loadingSpan.classList.add('hidden');
                                submitButton.disabled = false;
                            }
                        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                            console.log('Payment succeeded, creating order');
                            
                            // Call Livewire method to create the order
                            try {
                                const result = await @this.call('createOrderAfterPayment');
                                console.log('Order creation call completed', result);
                                
                                // Use the order number from the result if available, otherwise use the temp one
                                const realOrderNumber = result?.order_number || orderNumber;
                                
                                // Build the return URL properly for redirect after order creation
                                const baseUrl = '{{ url('/') }}';
                                const locale = '{{ app()->getLocale() }}';
                                const returnUrl = locale === 'es' 
                                    ? baseUrl + '/es/pedido-confirmado/' + realOrderNumber
                                    : baseUrl + '/en/order-confirmed/' + realOrderNumber;
                                
                                console.log('Redirecting to success page with order number:', realOrderNumber, returnUrl);
                                window.location.href = returnUrl;
                                
                            } catch (orderError) {
                                console.error('Order creation failed:', orderError);
                                alert('Payment succeeded but order creation failed. Please contact support with your payment details.');
                                
                                // Reset loading state
                                const textSpan = document.getElementById('submit-payment-text');
                                const loadingSpan = document.getElementById('submit-payment-loading');
                                if (textSpan && loadingSpan) {
                                    textSpan.classList.remove('hidden');
                                    loadingSpan.classList.add('hidden');
                                    submitButton.disabled = false;
                                }
                            }
                        }
                    });
                    console.log('Payment submit handler attached');
                }
            } else {
                console.error('Payment element container #payment-element not found');
            }
        } catch (error) {
            console.error('Error initializing Stripe:', error);
        }
    }
    
    // Simple approach - just listen for the stripe-ready event
    Livewire.on('stripe-ready', (event) => {
        console.log('Received stripe-ready event:', event);
        const data = event[0] || event;
        
        if (data.clientSecret && data.orderNumber) {
            console.log('Initializing Stripe with clientSecret and orderNumber from event');
            
            // Small delay to ensure DOM is ready
            setTimeout(() => {
                initializeStripe(data.clientSecret, data.orderNumber);
            }, 100);
        }
    });
    
    // Also check if values are already set on page load
    @if($clientSecret && $orderNumber)
        console.log('Values already available on page load, initializing Stripe');
        setTimeout(() => {
            initializeStripe('{{ $clientSecret }}', '{{ $orderNumber }}');
        }, 100);
    @endif
});
</script>