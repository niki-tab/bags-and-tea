<?php

namespace Src\Cart\Frontend;

use Livewire\Component;
use Src\Cart\Application\RetrieveCartContents;
use Src\Cart\Application\UpdateCartItemQuantity;
use Src\Cart\Application\RemoveItemFromCart;
use Src\Cart\Application\ClearCart;
use Src\Cart\Infrastructure\EloquentCartRepository;
use Src\Order\Application\CalculateOrderFees;
use Src\Order\Application\DetectUserCountry;

class CartPage extends Component
{
    public array $cartItems = [];
    public float $subtotal = 0;
    public float $totalPrice = 0;
    public int $totalItems = 0;
    public bool $isLoading = false;
    public array $fees = [];
    public ?array $shipping = null;
    public float $totalFees = 0;
    public bool $analyticsConsent = false;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $retrieveCartContents = new RetrieveCartContents(new EloquentCartRepository());
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartData = $retrieveCartContents($userId, $sessionId);
        
        $this->cartItems = $cartData['items'] ?? [];
        $this->totalItems = $cartData['total_items'] ?? 0;
        $this->calculateTotalPrice();
        $this->calculateFeesAndShipping();
    }

    public function updateQuantity(string $productId, int $quantity)
    {
        $this->isLoading = true;

        try {
            $updateCartItemQuantity = new UpdateCartItemQuantity(new EloquentCartRepository());
            
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $updateCartItemQuantity($productId, $quantity, $userId, $sessionId);
            
            $this->loadCart();
            $this->dispatch('cartUpdated');
            
        } catch (\InvalidArgumentException $e) {
            // Handle specific stock validation errors
            if (str_contains($e->getMessage(), 'Insufficient stock')) {
                session()->flash('cart-error', $e->getMessage());
            } else {
                session()->flash('cart-error', trans('components/cart.error-updating'));
            }
        } catch (\Exception $e) {
            session()->flash('cart-error', trans('components/cart.error-updating'));
        } finally {
            $this->isLoading = false;
        }
    }

    public function removeItem(string $productId)
    {
        $this->isLoading = true;

        try {
            $removeItemFromCart = new RemoveItemFromCart(new EloquentCartRepository());
            
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $removeItemFromCart($productId, $userId, $sessionId);
            
            $this->loadCart();
            $this->dispatch('cartUpdated');
            
            session()->flash('cart-success', trans('components/cart.item-removed'));
            
        } catch (\Exception $e) {
            session()->flash('cart-error', trans('components/cart.error-removing'));
        } finally {
            $this->isLoading = false;
        }
    }

    public function clearCart()
    {
        $this->isLoading = true;

        try {
            $clearCart = new ClearCart(new EloquentCartRepository());
            
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $clearCart($userId, $sessionId);
            
            $this->loadCart();
            $this->dispatch('cartUpdated');
            
            session()->flash('cart-success', trans('components/cart.cart-cleared'));
            
        } catch (\Exception $e) {
            session()->flash('cart-error', trans('components/cart.error-clearing'));
        } finally {
            $this->isLoading = false;
        }
    }

    private function calculateTotalPrice()
    {
        $this->subtotal = 0;
        
        foreach ($this->cartItems as $item) {
            if (isset($item['product']) && isset($item['quantity'])) {
                $price = $item['product']['price'] ?? 0;
                $this->subtotal += $price * $item['quantity'];
            }
        }
        
        // Total includes subtotal + fees + shipping
        $shippingAmount = $this->shipping['amount'] ?? 0;
        $this->totalPrice = $this->subtotal + $this->totalFees + $shippingAmount;
    }

    private function calculateFeesAndShipping()
    {
        if ($this->subtotal <= 0) {
            $this->fees = [];
            $this->shipping = null;
            $this->totalFees = 0;
            return;
        }

        $calculateOrderFees = new CalculateOrderFees();
        $detectUserCountry = new DetectUserCountry();
        
        // Set analytics consent in session so DetectUserCountry can access it
        session()->put('analytics_consent', $this->analyticsConsent);
        
        // Detect user's country based on IP (if consent given) or use default
        $userCountry = $detectUserCountry();
        
        $result = $calculateOrderFees($this->subtotal, $userCountry);
        
        $this->fees = $result['fees'];
        $this->totalFees = $result['total_fees'];
        $this->shipping = $result['shipping'];
        
        // Recalculate total price with fees and shipping
        $this->calculateTotalPrice();
    }

    public function updateAnalyticsConsent(bool $consent)
    {
        $this->analyticsConsent = $consent;
        $this->calculateFeesAndShipping();
    }

    public function render()
    {
        return view('livewire.cart.cart-page')->extends('layouts.app');
    }
}