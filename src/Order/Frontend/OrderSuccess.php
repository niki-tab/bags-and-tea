<?php

namespace Src\Order\Frontend;

use Livewire\Component;
use Src\Order\Infrastructure\EloquentOrderRepository;
use Src\Order\Application\ProcessStripePayment;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;

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
        
        // First validate security token before loading order
        if (!$this->validateSecurityToken()) {
            $this->authorized = false;
            return;
        }
        
        $this->loadOrder();
        
        // Check if payment needs to be verified
        if ($this->orderFound && $this->order && $this->order['payment_status'] === 'pending') {
            $this->verifyPaymentStatus();
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