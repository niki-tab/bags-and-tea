<?php

namespace Src\Cart\Frontend;

use Livewire\Component;
use Src\Cart\Application\RetrieveCartContents;
use Src\Cart\Application\UpdateCartItemQuantity;
use Src\Cart\Application\RemoveItemFromCart;
use Src\Cart\Application\ClearCart;
use Src\Cart\Infrastructure\EloquentCartRepository;

class CartPage extends Component
{
    public array $cartItems = [];
    public float $totalPrice = 0;
    public int $totalItems = 0;
    public bool $isLoading = false;

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
        $this->totalPrice = 0;
        
        foreach ($this->cartItems as $item) {
            if (isset($item['product']) && isset($item['quantity'])) {
                $price = $item['product']['price'] ?? 0;
                $this->totalPrice += $price * $item['quantity'];
            }
        }
    }

    public function render()
    {
        return view('livewire.cart.cart-page')->extends('layouts.app');
    }
}