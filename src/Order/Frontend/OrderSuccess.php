<?php

namespace Src\Order\Frontend;

use Livewire\Component;
use Src\Order\Infrastructure\EloquentOrderRepository;
use Src\Order\Application\ProcessStripePayment;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;
use Src\Cart\Infrastructure\EloquentCartRepository;
use Src\Order\Application\OrderCreator;
use App\Events\NewOrderCreated;

class OrderSuccess extends Component
{
    public string $orderNumber;
    public ?string $securityToken = null;
    public ?array $order = null;
    public bool $orderFound = false;
    public bool $authorized = true;

    public function mount(string $order_number)
    {
        $this->orderNumber = $order_number;
        $this->securityToken = request()->query('token');

        \Log::info('=== ORDER SUCCESS: mount START ===', [
            'order_number' => $order_number,
            'security_token' => $this->securityToken,
            'session_id' => session()->getId(),
            'is_temp_order' => str_starts_with($order_number, 'TEMP-'),
            'has_payment_intent' => request()->has('payment_intent'),
            'payment_intent' => request()->query('payment_intent'),
            'redirect_status' => request()->query('redirect_status'),
        ]);

        // Check if this is a redirect from PayPal/Klarna (has payment_intent in URL)
        $paymentIntentFromUrl = request()->query('payment_intent');
        $redirectStatus = request()->query('redirect_status');

        if ($paymentIntentFromUrl && $redirectStatus === 'succeeded') {
            \Log::info('=== ORDER SUCCESS: Detected redirect payment (PayPal/Klarna) ===', [
                'payment_intent' => $paymentIntentFromUrl,
                'redirect_status' => $redirectStatus,
            ]);

            // Check if this is a TEMP order that needs to be created
            if (str_starts_with($order_number, 'TEMP-')) {
                \Log::info('=== ORDER SUCCESS: TEMP order detected, attempting to create real order ===');
                $this->handleRedirectPayment($paymentIntentFromUrl, $order_number);
                return;
            }
        }

        // First validate security token before loading order
        if (!$this->validateSecurityToken()) {
            $this->authorized = false;
            \Log::warning('=== ORDER SUCCESS: Security token validation FAILED ===');
            return;
        }

        $this->loadOrder();

        // Check if payment needs to be verified
        if ($this->orderFound && $this->order && $this->order['payment_status'] === 'pending') {
            \Log::info('=== ORDER SUCCESS: Order found with pending payment, verifying ===');
            $this->verifyPaymentStatus();
        }
    }

    private function handleRedirectPayment(string $paymentIntentId, string $tempOrderNumber): void
    {
        \Log::info('=== ORDER SUCCESS: handleRedirectPayment START ===', [
            'payment_intent_id' => $paymentIntentId,
            'temp_order_number' => $tempOrderNumber,
        ]);

        // Get pending checkout data from session
        $pendingCheckout = session()->get('pending_checkout');

        \Log::info('=== ORDER SUCCESS: Pending checkout data from session ===', [
            'has_pending_checkout' => !is_null($pendingCheckout),
            'pending_temp_order' => $pendingCheckout['temp_order_number'] ?? 'N/A',
            'pending_payment_intent' => $pendingCheckout['payment_intent_id'] ?? 'N/A',
        ]);

        if (!$pendingCheckout) {
            \Log::error('=== ORDER SUCCESS: No pending checkout data in session! ===', [
                'session_id' => session()->getId(),
            ]);
            $this->authorized = false;
            return;
        }

        // Verify this is the right payment
        if ($pendingCheckout['temp_order_number'] !== $tempOrderNumber) {
            \Log::error('=== ORDER SUCCESS: Temp order number mismatch! ===', [
                'url_temp_order' => $tempOrderNumber,
                'session_temp_order' => $pendingCheckout['temp_order_number'],
            ]);
            $this->authorized = false;
            return;
        }

        // Verify payment succeeded with Stripe
        try {
            $stripePayment = new ProcessStripePayment();
            $paymentResult = $stripePayment->confirmPayment($paymentIntentId);

            \Log::info('=== ORDER SUCCESS: Stripe payment verification ===', [
                'success' => $paymentResult['success'],
                'status' => $paymentResult['status'] ?? 'N/A',
            ]);

            if (!$paymentResult['success'] || $paymentResult['status'] !== 'succeeded') {
                \Log::error('=== ORDER SUCCESS: Payment not succeeded! ===', $paymentResult);
                $this->authorized = false;
                return;
            }

            // Get the actual payment method used from Stripe
            $actualPaymentMethod = $paymentResult['payment_method_type'] ?? $pendingCheckout['payment_method'];

            // Map Stripe payment method types to our internal names
            $paymentMethodMap = [
                'paypal' => 'stripe_paypal',
                'card' => 'stripe_card',
                'klarna' => 'stripe_klarna',
            ];
            $paymentMethodToStore = $paymentMethodMap[$actualPaymentMethod] ?? 'stripe_' . $actualPaymentMethod;

            \Log::info('=== ORDER SUCCESS: Creating order from pending checkout data ===', [
                'stripe_payment_method' => $actualPaymentMethod,
                'stored_payment_method' => $paymentMethodToStore,
            ]);

            $customerData = [
                'email' => $pendingCheckout['customer_email'],
                'name' => $pendingCheckout['customer_name'],
                'phone' => $pendingCheckout['customer_phone'] ?? '',
            ];

            $billingAddress = $pendingCheckout['billing_address'];
            $shippingAddress = $pendingCheckout['shipping_address'];

            // If same_as_billing, use shipping for billing
            if ($pendingCheckout['same_as_billing']) {
                $billingAddress = array_merge($shippingAddress, ['vat_id' => $billingAddress['vat_id'] ?? '']);
            }

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
                $pendingCheckout['discount_code'] ?: null,
                $paymentMethodToStore
            );

            \Log::info('=== ORDER SUCCESS: Order created successfully ===', [
                'order_number' => $orderResult['order_number'],
                'order_id' => $orderResult['order_id'],
            ]);

            // Update order with payment intent ID and mark as paid
            \DB::table('orders')
                ->where('id', $orderResult['order_id'])
                ->update([
                    'payment_intent_id' => $paymentIntentId,
                    'payment_status' => 'paid',
                    'updated_at' => now(),
                ]);

            // Clear the pending checkout data
            session()->forget('pending_checkout');

            // Dispatch NewOrderCreated event
            NewOrderCreated::dispatch($orderResult['order_number']);

            \Log::info('=== ORDER SUCCESS: Order finalized, redirecting ===', [
                'order_number' => $orderResult['order_number'],
            ]);

            // Update the order number and load the order
            $this->orderNumber = $orderResult['order_number'];

            // Get security token for redirect
            $order = \DB::table('orders')->where('order_number', $this->orderNumber)->first();
            $securityToken = $order ? substr($order->id, 0, 8) : null;

            // Instead of redirecting, just load the order and display success
            // The order was just created, so load it
            $this->securityToken = $securityToken;
            $this->loadOrder();
            $this->authorized = true;

            \Log::info('=== ORDER SUCCESS: Order loaded after PayPal payment ===', [
                'order_number' => $this->orderNumber,
                'order_found' => $this->orderFound,
            ]);

        } catch (\Exception $e) {
            \Log::error('=== ORDER SUCCESS: Exception during order creation ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->authorized = false;
        }
    }

    private function loadOrder()
    {
        $orderRepository = new EloquentOrderRepository();
        $this->order = $orderRepository->retrieveOrderByNumber($this->orderNumber);
        $this->orderFound = !is_null($this->order);
    }

    private function verifyPaymentStatus()
    {
        if (!$this->order['payment_intent_id']) {
            return; // No payment intent to verify
        }

        try {
            $stripePayment = new ProcessStripePayment();
            $paymentResult = $stripePayment->confirmPayment($this->order['payment_intent_id']);
            
            if ($paymentResult['success'] && $paymentResult['status'] === 'succeeded') {
                // Update payment status to paid
                $orderModel = OrderEloquentModel::where('order_number', $this->orderNumber)->first();
                if ($orderModel) {
                    $orderModel->update([
                        'payment_status' => 'paid',
                        'updated_at' => now(),
                    ]);
                    
                    // Reload the order with updated status
                    $this->loadOrder();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to verify payment status for order: ' . $this->orderNumber, [
                'error' => $e->getMessage()
            ]);
        }
    }

    private function validateSecurityToken(): bool
    {
        // If no token provided, deny access
        if (!$this->securityToken) {
            \Log::warning('Order access attempt without security token', [
                'order_number' => $this->orderNumber,
                'ip' => request()->ip()
            ]);
            return false;
        }

        // Get the order to validate token
        $orderModel = OrderEloquentModel::where('order_number', $this->orderNumber)->first();
        
        if (!$orderModel) {
            \Log::warning('Order access attempt with invalid order number', [
                'order_number' => $this->orderNumber,
                'token' => $this->securityToken,
                'ip' => request()->ip()
            ]);
            return false;
        }

        // Validate that the token matches the first 8 characters of the order UUID
        $expectedToken = substr($orderModel->id, 0, 8);
        
        if ($this->securityToken !== $expectedToken) {
            \Log::warning('Order access attempt with invalid security token', [
                'order_number' => $this->orderNumber,
                'provided_token' => $this->securityToken,
                'ip' => request()->ip()
            ]);
            return false;
        }

        return true;
    }

    public function render()
    {
        return view('livewire.order.order-success')->extends('layouts.app');
    }
}