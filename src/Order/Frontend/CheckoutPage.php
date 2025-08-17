<?php

namespace Src\Order\Frontend;

use Livewire\Component;
use Src\Cart\Application\RetrieveCartContents;
use Src\Cart\Infrastructure\EloquentCartRepository;
use Src\Order\Application\OrderCreator;
use Src\Order\Infrastructure\EloquentOrderRepository;
use Src\Order\Application\ProcessStripePayment;
use Src\Order\Application\CalculateOrderFees;
use Src\Order\Shipping\Infrastructure\Eloquent\ShippingRateEloquentModel;
use App\Events\NewOrderCreated;

class CheckoutPage extends Component
{
    // Customer Information
    public string $customer_email = '';
    public string $customer_name = '';
    public string $customer_phone = '';

    // Billing Address
    public string $billing_first_name = '';
    public string $billing_last_name = '';
    public string $billing_line1 = '';
    public string $billing_line2 = '';
    public string $billing_city = '';
    public string $billing_postal_code = '';
    public string $billing_country = 'ES';
    public string $billing_state = '';
    public string $billing_vat_id = '';

    // Shipping Address
    public string $shipping_first_name = '';
    public string $shipping_last_name = '';
    public string $shipping_line1 = '';
    public string $shipping_line2 = '';
    public string $shipping_city = '';
    public string $shipping_postal_code = '';
    public string $shipping_country = 'ES';
    public string $shipping_state = '';
    public bool $same_as_billing = true; // This means billing same as shipping

    // Payment
    public string $payment_method = 'stripe_card';
    public string $discount_code = '';

    // Cart and Order Data
    public array $cartItems = [];
    public float $subtotal = 0;
    public float $shippingCost = 0;
    public array $shippingDetails = [];
    public float $totalAmount = 0;
    public int $totalItems = 0;
    public bool $isLoading = false;
    public bool $shippingAvailable = true;
    public string $shippingError = '';

    // Checkout Steps (keeping for backward compatibility but not using step logic)
    public int $currentStep = 1;
    public array $completedSteps = [];
    
    // Stripe Payment
    public ?string $paymentIntentId = null;
    public ?string $clientSecret = null;
    public ?string $orderNumber = null;
    public array $fees = [];
    public float $totalFees = 0;

    public function mount()
    {
        $this->loadCart();
        $this->prefillCustomerData();
    }

    public function updated($propertyName)
    {
        // Recalculate totals when shipping country changes
        if ($propertyName === 'shipping_country') {
            $this->calculateTotals();
        }
        
        // Also recalculate if billing country changes (might affect fees)
        if ($propertyName === 'billing_country') {
            $this->calculateTotals();
        }
    }

    public function getAvailableCountries()
    {
        // Get country names from translations based on current locale
        $countries = trans('components/checkout.countries');

        // Sort alphabetically by country name
        asort($countries);

        return $countries;
    }

    public function loadCart()
    {
        $retrieveCartContents = new RetrieveCartContents(new EloquentCartRepository());
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartData = $retrieveCartContents($userId, $sessionId);
        
        $this->cartItems = $cartData['items'] ?? [];
        $this->totalItems = $cartData['total_items'] ?? 0;
        $this->calculateTotals();
        
        if (empty($this->cartItems)) {
            session()->flash('checkout-error', trans('components/checkout.empty-cart'));
            return redirect()->route(app()->getLocale() === 'es' ? 'cart.show.es' : 'cart.show.en', ['locale' => app()->getLocale()]);
        }
    }

    private function prefillCustomerData()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->customer_email = $user->email;
            $this->customer_name = $user->name;
        }
    }

    private function calculateTotals()
    {
        $this->subtotal = 0;
        
        foreach ($this->cartItems as $item) {
            if (isset($item['product']) && isset($item['quantity'])) {
                $price = $item['product']['price'] ?? 0;
                $this->subtotal += $price * $item['quantity'];
            }
        }
        
        // Calculate fees and shipping
        $this->calculateFeesAndShipping();
        
        // Calculate total: subtotal + fees + shipping
        $this->totalAmount = $this->subtotal + $this->totalFees + $this->shippingCost;
    }

    private function calculateFeesAndShipping()
    {
        if ($this->subtotal <= 0) {
            $this->fees = [];
            $this->totalFees = 0;
            $this->shippingCost = 0;
            return;
        }

        $calculateOrderFees = new CalculateOrderFees();
        
        // Use shipping country for calculation
        $shippingCountry = $this->shipping_country ?: 'ES';
        
        $result = $calculateOrderFees($this->subtotal, $shippingCountry);
        
        $this->fees = $result['fees'];
        $this->totalFees = $result['total_fees'];
        
        // Extract shipping cost and availability
        if ($result['shipping']) {
            if (isset($result['shipping']['is_available']) && !$result['shipping']['is_available']) {
                // Shipping not available
                $this->shippingAvailable = false;
                $this->shippingCost = 0;
                $this->shippingError = $result['shipping']['error_message'] ?? 'shipping_not_available';
                $this->shippingDetails = [];
            } else {
                // Shipping available
                $this->shippingAvailable = true;
                $this->shippingCost = $result['shipping']['amount'];
                $this->shippingError = '';
                $this->shippingDetails = $result['shipping'];
            }
        } else {
            $this->shippingCost = 0;
            $this->shippingAvailable = true;
            $this->shippingError = '';
            $this->shippingDetails = [];
        }
    }

    public function updatedSameAsBilling()
    {
        if ($this->same_as_billing) {
            // Copy shipping address to billing address
            $this->billing_first_name = $this->shipping_first_name;
            $this->billing_last_name = $this->shipping_last_name;
            $this->billing_line1 = $this->shipping_line1;
            $this->billing_line2 = $this->shipping_line2;
            $this->billing_city = $this->shipping_city;
            $this->billing_postal_code = $this->shipping_postal_code;
            $this->billing_country = $this->shipping_country;
            $this->billing_state = $this->shipping_state;
            // Clear VAT ID when using same as billing since shipping doesn't have VAT ID
            $this->billing_vat_id = '';
        }
    }

    public function nextStep()
    {
        if ($this->validateCurrentStep()) {
            $this->completedSteps[] = $this->currentStep;
            
            // If moving from Step 3 (Payment Method) to Step 4, process the order and create payment intent
            if ($this->currentStep == 3) {
                $this->processOrder();
            } else {
                $this->currentStep++;
            }
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step)
    {
        if ($step <= $this->currentStep || in_array($step - 1, $this->completedSteps)) {
            $this->currentStep = $step;
        }
    }

    public function processCompleteOrder()
    {
        \Log::info('ProcessCompleteOrder called');
        
        try {
            $this->validateAllFields();
            \Log::info('Validation passed, creating order and payment intent');
            $this->createOrderAndPaymentIntent();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            // Re-throw the validation exception so Livewire can display the errors
            throw $e;
        }
    }

    public function createPaymentIntentOnly()
    {
        try {
            \Log::info('Starting createPaymentIntentOnly');
            
            // First validate all fields
            $this->validateAllFields();
            
            $this->isLoading = true;

            // Generate a temporary order number for the payment intent
            $tempOrderNumber = 'TEMP-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // If same_as_billing is true, copy shipping to billing
            if ($this->same_as_billing) {
                $this->billing_first_name = $this->shipping_first_name;
                $this->billing_last_name = $this->shipping_last_name;
                $this->billing_line1 = $this->shipping_line1;
                $this->billing_line2 = $this->shipping_line2;
                $this->billing_city = $this->shipping_city;
                $this->billing_postal_code = $this->shipping_postal_code;
                $this->billing_country = $this->shipping_country;
                $this->billing_state = $this->shipping_state;
                \Log::info('Copied shipping address to billing address');
            }

            $shippingAddress = [
                'first_name' => $this->shipping_first_name,
                'last_name' => $this->shipping_last_name,
                'line1' => $this->shipping_line1,
                'line2' => $this->shipping_line2,
                'city' => $this->shipping_city,
                'postal_code' => $this->shipping_postal_code,
                'country' => $this->shipping_country,
                'state' => $this->shipping_state,
            ];

            // Process payment with Stripe (create payment intent only)
            \Log::info('Creating Stripe payment intent without order');
            $stripePayment = new ProcessStripePayment();
            
            $orderData = [
                'order_number' => $tempOrderNumber,
                'total_amount' => $this->totalAmount,
                'currency' => 'EUR',
                'payment_method' => $this->payment_method,
                'customer_email' => $this->customer_email,
                'customer_name' => $this->customer_name,
                'shipping_address' => $shippingAddress,
            ];

            $paymentResult = $stripePayment->createPaymentIntent($orderData);

            if (!$paymentResult['success']) {
                \Log::error('Stripe payment intent creation failed', ['message' => $paymentResult['message']]);
                session()->flash('checkout-error', 'Payment processing failed: ' . $paymentResult['message']);
                return;
            }

            // Store payment intent for frontend processing
            $this->paymentIntentId = $paymentResult['payment_intent_id'];
            $this->clientSecret = $paymentResult['client_secret'];
            $this->orderNumber = $tempOrderNumber; // Store temp order number
            
            \Log::info('Payment intent created successfully', [
                'payment_intent_id' => $this->paymentIntentId,
                'client_secret_length' => strlen($this->clientSecret),
                'total_amount' => $this->totalAmount,
                'temp_order_number' => $tempOrderNumber,
            ]);
            
            // Dispatch event to frontend that clientSecret is ready
            \Log::info('Dispatching stripe-ready event to frontend');
            $this->dispatch('stripe-ready', [
                'clientSecret' => $this->clientSecret,
                'orderNumber' => $this->orderNumber
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            // Re-throw the validation exception so Livewire can display the errors
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Payment intent creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('checkout-error', trans('components/checkout.order-creation-failed'));
        } finally {
            $this->isLoading = false;
        }
    }

    public function createOrderAfterPayment()
    {
        \Log::info('createOrderAfterPayment called');
        
        try {
            $this->isLoading = true;

            $customerData = [
                'email' => $this->customer_email,
                'name' => $this->customer_name,
                'phone' => $this->customer_phone,
            ];

            // If same_as_billing is true, copy shipping to billing
            if ($this->same_as_billing) {
                $this->billing_first_name = $this->shipping_first_name;
                $this->billing_last_name = $this->shipping_last_name;
                $this->billing_line1 = $this->shipping_line1;
                $this->billing_line2 = $this->shipping_line2;
                $this->billing_city = $this->shipping_city;
                $this->billing_postal_code = $this->shipping_postal_code;
                $this->billing_country = $this->shipping_country;
                $this->billing_state = $this->shipping_state;
                // Keep VAT ID as it's billing-specific
                \Log::info('Copied shipping address to billing address');
            }

            $billingAddress = [
                'first_name' => $this->billing_first_name,
                'last_name' => $this->billing_last_name,
                'line1' => $this->billing_line1,
                'line2' => $this->billing_line2,
                'city' => $this->billing_city,
                'postal_code' => $this->billing_postal_code,
                'country' => $this->billing_country,
                'state' => $this->billing_state,
                'vat_id' => $this->billing_vat_id,
            ];

            $shippingAddress = [
                'first_name' => $this->shipping_first_name,
                'last_name' => $this->shipping_last_name,
                'line1' => $this->shipping_line1,
                'line2' => $this->shipping_line2,
                'city' => $this->shipping_city,
                'postal_code' => $this->shipping_postal_code,
                'country' => $this->shipping_country,
                'state' => $this->shipping_state,
            ];

            \Log::info('Creating order with OrderCreator');
            $orderCreator = new OrderCreator(
                new EloquentOrderRepository(),
                new EloquentCartRepository()
            );

            $orderResult = $orderCreator(
                $customerData,
                $billingAddress,
                $shippingAddress,
                auth()->id(),
                session()->getId(),
                $this->discount_code ?: null,
                $this->payment_method
            );
            \Log::info('Order created successfully', ['order_number' => $orderResult['order_number']]);

            $this->orderNumber = $orderResult['order_number'];
            
            // Update order with payment intent ID
            $this->updateOrderPaymentIntent($orderResult['order_id'], $this->paymentIntentId);
            
            \Log::info('Order creation completed', [
                'order_number' => $this->orderNumber,
                'payment_intent_id' => $this->paymentIntentId,
            ]);

            // Dispatch an event to update the frontend with the real order number
            $this->dispatch('order-created', [
                'orderNumber' => $this->orderNumber
            ]);

            // Dispatch the NewOrderCreated event to trigger notifications and other processes
            \Log::info('Dispatching NewOrderCreated event for order: ' . $this->orderNumber);
            NewOrderCreated::dispatch($this->orderNumber);

            // Get the order to generate security token
            $order = \DB::table('orders')->where('order_number', $this->orderNumber)->first();
            $securityToken = $order ? substr($order->id, 0, 8) : null;

            return [
                'success' => true,
                'order_number' => $this->orderNumber,
                'security_token' => $securityToken
            ];

        } catch (\Exception $e) {
            \Log::error('Order creation after payment failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('checkout-error', trans('components/checkout.order-creation-failed'));
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        } finally {
            $this->isLoading = false;
        }
    }

    private function createOrderAndPaymentIntent()
    {
        try {
            \Log::info('Starting createOrderAndPaymentIntent');
            $this->isLoading = true;

            $customerData = [
                'email' => $this->customer_email,
                'name' => $this->customer_name,
                'phone' => $this->customer_phone,
            ];

            // If same_as_billing is true, copy shipping to billing
            if ($this->same_as_billing) {
                $this->billing_first_name = $this->shipping_first_name;
                $this->billing_last_name = $this->shipping_last_name;
                $this->billing_line1 = $this->shipping_line1;
                $this->billing_line2 = $this->shipping_line2;
                $this->billing_city = $this->shipping_city;
                $this->billing_postal_code = $this->shipping_postal_code;
                $this->billing_country = $this->shipping_country;
                $this->billing_state = $this->shipping_state;
                // Keep VAT ID as it's billing-specific
                \Log::info('Copied shipping address to billing address');
            }

            $billingAddress = [
                'first_name' => $this->billing_first_name,
                'last_name' => $this->billing_last_name,
                'line1' => $this->billing_line1,
                'line2' => $this->billing_line2,
                'city' => $this->billing_city,
                'postal_code' => $this->billing_postal_code,
                'country' => $this->billing_country,
                'state' => $this->billing_state,
                'vat_id' => $this->billing_vat_id,
            ];

            $shippingAddress = [
                'first_name' => $this->shipping_first_name,
                'last_name' => $this->shipping_last_name,
                'line1' => $this->shipping_line1,
                'line2' => $this->shipping_line2,
                'city' => $this->shipping_city,
                'postal_code' => $this->shipping_postal_code,
                'country' => $this->shipping_country,
                'state' => $this->shipping_state,
            ];

            \Log::info('Creating order with OrderCreator');
            $orderCreator = new OrderCreator(
                new EloquentOrderRepository(),
                new EloquentCartRepository()
            );

            $orderResult = $orderCreator(
                $customerData,
                $billingAddress,
                $shippingAddress,
                auth()->id(),
                session()->getId(),
                $this->discount_code ?: null,
                $this->payment_method
            );
            \Log::info('Order created successfully', ['order_number' => $orderResult['order_number']]);

            // Process payment with Stripe
            \Log::info('Creating Stripe payment intent');
            $stripePayment = new ProcessStripePayment();
            
            $orderData = [
                'order_number' => $orderResult['order_number'],
                'total_amount' => $this->totalAmount,
                'currency' => 'EUR',
                'payment_method' => $this->payment_method,
                'customer_email' => $this->customer_email,
                'customer_name' => $this->customer_name,
                'shipping_address' => $shippingAddress,
            ];

            $paymentResult = $stripePayment->createPaymentIntent($orderData);

            if (!$paymentResult['success']) {
                \Log::error('Stripe payment intent creation failed', ['message' => $paymentResult['message']]);
                session()->flash('checkout-error', 'Payment processing failed: ' . $paymentResult['message']);
                return;
            }

            // Store payment intent for frontend processing
            $this->paymentIntentId = $paymentResult['payment_intent_id'];
            $this->clientSecret = $paymentResult['client_secret'];
            $this->orderNumber = $orderResult['order_number'];
            
            \Log::info('Payment intent created successfully', [
                'payment_intent_id' => $this->paymentIntentId,
                'client_secret_length' => strlen($this->clientSecret),
                'client_secret_preview' => substr($this->clientSecret, 0, 20) . '...',
                'total_amount' => $this->totalAmount,
                'order_number' => $this->orderNumber,
            ]);
            
            // Update order with payment intent ID
            $this->updateOrderPaymentIntent($orderResult['order_id'], $paymentResult['payment_intent_id']);
            
            // Dispatch event to frontend that clientSecret is ready
            \Log::info('Dispatching stripe-ready event to frontend');
            $this->dispatch('stripe-ready', [
                'clientSecret' => $this->clientSecret,
                'orderNumber' => $this->orderNumber
            ]);

        } catch (\Exception $e) {
            \Log::error('Order creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('checkout-error', trans('components/checkout.order-creation-failed'));
        } finally {
            $this->isLoading = false;
        }
    }

    private function validateAllFields(): void
    {
        $this->resetValidation();

        // Check if shipping is available to the selected country
        if (!$this->shippingAvailable) {
            $this->addError('shipping_country', trans('components/checkout.shipping-not-available'));
            throw new \Exception('Shipping not available to selected country');
        }

        // Validate all fields at once
        $rules = [
            // Customer Information
            'customer_email' => 'required|email',
            'customer_name' => 'required|string|min:2',
            'customer_phone' => 'nullable|string',
            
            // Shipping Address (always required)
            'shipping_line1' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'shipping_country' => 'required|string',
            
            // Payment Method
            'payment_method' => 'required|string|in:stripe_card',
        ];

        // Add billing address validation only if not same as shipping
        if (!$this->same_as_billing) {
            $rules['billing_line1'] = 'required|string';
            $rules['billing_city'] = 'required|string';
            $rules['billing_postal_code'] = 'required|string';
            $rules['billing_country'] = 'required|string';
        }

        $this->validate($rules, [
            // Customer Information
            'customer_email.required' => trans('components/checkout.validation.email-required'),
            'customer_email.email' => trans('components/checkout.validation.email-invalid'),
            'customer_name.required' => trans('components/checkout.validation.name-required'),
            'customer_name.min' => trans('components/checkout.validation.name-min'),
            
            // Shipping Address
            'shipping_line1.required' => trans('components/checkout.validation.shipping-address-required'),
            'shipping_city.required' => trans('components/checkout.validation.shipping-city-required'),
            'shipping_postal_code.required' => trans('components/checkout.validation.shipping-postal-required'),
            'shipping_country.required' => trans('components/checkout.validation.shipping-country-required'),
            
            // Billing Address
            'billing_line1.required' => trans('components/checkout.validation.billing-address-required'),
            'billing_city.required' => trans('components/checkout.validation.billing-city-required'),
            'billing_postal_code.required' => trans('components/checkout.validation.billing-postal-required'),
            'billing_country.required' => trans('components/checkout.validation.billing-country-required'),
            
            // Payment Method
            'payment_method.required' => trans('components/checkout.validation.payment-method-required'),
            'payment_method.in' => trans('components/checkout.validation.payment-method-invalid'),
        ]);
    }

    private function validateCurrentStep(): bool
    {
        $this->resetValidation();

        switch ($this->currentStep) {
            case 1: // Customer Information
                $this->validate([
                    'customer_email' => 'required|email',
                    'customer_name' => 'required|string|min:2',
                    'customer_phone' => 'nullable|string',
                ]);
                break;

            case 2: // Addresses
                // Always validate shipping address (shown first)
                $this->validate([
                    'shipping_line1' => 'required|string',
                    'shipping_city' => 'required|string',
                    'shipping_postal_code' => 'required|string',
                    'shipping_country' => 'required|string',
                ]);

                // If billing is different from shipping, validate billing fields
                if (!$this->same_as_billing) {
                    $this->validate([
                        'billing_line1' => 'required|string',
                        'billing_city' => 'required|string',
                        'billing_postal_code' => 'required|string',
                        'billing_country' => 'required|string',
                    ]);
                } else {
                    // Copy shipping to billing
                    $this->updatedSameAsBilling();
                }
                break;

            case 3: // Payment Method
                $this->validate([
                    'payment_method' => 'required|in:stripe_card,stripe_klarna,stripe_paypal',
                ]);
                break;
        }

        return true;
    }

    public function applyDiscountCode()
    {
        if (empty($this->discount_code)) {
            session()->flash('discount-error', trans('components/checkout.enter-discount-code'));
            return;
        }

        // TODO: Implement discount code validation and application
        session()->flash('discount-error', trans('components/checkout.invalid-discount-code'));
    }

    public function processOrder()
    {
        try {
            $this->isLoading = true;

            // Validate all steps
            for ($step = 1; $step <= 3; $step++) {
                $this->currentStep = $step;
                if (!$this->validateCurrentStep()) {
                    $this->isLoading = false;
                    return;
                }
            }

            $customerData = [
                'email' => $this->customer_email,
                'name' => $this->customer_name,
                'phone' => $this->customer_phone,
            ];

            $billingAddress = [
                'first_name' => $this->billing_first_name,
                'last_name' => $this->billing_last_name,
                'line1' => $this->billing_line1,
                'line2' => $this->billing_line2,
                'city' => $this->billing_city,
                'postal_code' => $this->billing_postal_code,
                'country' => $this->billing_country,
                'state' => $this->billing_state,
                'vat_id' => $this->billing_vat_id,
            ];

            $shippingAddress = [
                'first_name' => $this->shipping_first_name,
                'last_name' => $this->shipping_last_name,
                'line1' => $this->shipping_line1,
                'line2' => $this->shipping_line2,
                'city' => $this->shipping_city,
                'postal_code' => $this->shipping_postal_code,
                'country' => $this->shipping_country,
                'state' => $this->shipping_state,
            ];

            $orderCreator = new OrderCreator(
                new EloquentOrderRepository(),
                new EloquentCartRepository()
            );

            $orderResult = $orderCreator(
                $customerData,
                $billingAddress,
                $shippingAddress,
                auth()->id(),
                session()->getId(),
                $this->discount_code ?: null,
                $this->payment_method
            );

            // Process payment with Stripe
            $stripePayment = new ProcessStripePayment();
            
            $orderData = [
                'order_number' => $orderResult['order_number'],
                'total_amount' => $this->totalAmount,
                'currency' => 'EUR',
                'payment_method' => $this->payment_method,
                'customer_email' => $this->customer_email,
                'customer_name' => $this->customer_name,
                'shipping_address' => $shippingAddress,
            ];

            $paymentResult = $stripePayment->createPaymentIntent($orderData);

            if (!$paymentResult['success']) {
                session()->flash('checkout-error', 'Payment processing failed: ' . $paymentResult['message']);
                return;
            }

            // Store payment intent for frontend processing
            $this->paymentIntentId = $paymentResult['payment_intent_id'];
            $this->clientSecret = $paymentResult['client_secret'];
            $this->orderNumber = $orderResult['order_number'];
            
            \Log::info('Payment intent created successfully', [
                'payment_intent_id' => $this->paymentIntentId,
                'client_secret_length' => strlen($this->clientSecret),
                'total_amount' => $this->totalAmount,
                'order_number' => $this->orderNumber,
            ]);
            
            // Update order with payment intent ID
            $this->updateOrderPaymentIntent($orderResult['order_id'], $paymentResult['payment_intent_id']);
            
            // Move to final step for payment confirmation
            $this->currentStep = 4;

        } catch (\Exception $e) {
            session()->flash('checkout-error', trans('components/checkout.order-creation-failed'));
            \Log::error('Order creation failed: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    private function updateOrderPaymentIntent(string $orderId, string $paymentIntentId): void
    {
        \DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'payment_intent_id' => $paymentIntentId,
                'updated_at' => now(),
            ]);
    }

    public function confirmPayment()
    {
        if (!$this->paymentIntentId) {
            session()->flash('checkout-error', 'No payment intent found.');
            return;
        }

        try {
            $stripePayment = new ProcessStripePayment();
            $result = $stripePayment->confirmPayment($this->paymentIntentId);

            if ($result['success'] && $result['status'] === 'succeeded') {
                // Update order payment status
                \DB::table('orders')
                    ->where('payment_intent_id', $this->paymentIntentId)
                    ->update([
                        'payment_status' => 'paid',
                        'updated_at' => now(),
                    ]);

                // Get order number for redirect
                $order = \DB::table('orders')
                    ->where('payment_intent_id', $this->paymentIntentId)
                    ->first();

                session()->flash('order-success', trans('components/checkout.payment-successful'));
                return redirect()->route('checkout.success', [
                    'locale' => app()->getLocale(),
                    'order_number' => $order->order_number
                ]);
            } else {
                session()->flash('checkout-error', 'Payment confirmation failed.');
            }
        } catch (\Exception $e) {
            session()->flash('checkout-error', 'Payment processing error: ' . $e->getMessage());
            \Log::error('Payment confirmation failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order.checkout-page', [
            'availableCountries' => $this->getAvailableCountries()
        ])->extends('layouts.app');
    }
}