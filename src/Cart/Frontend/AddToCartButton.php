<?php

namespace Src\Cart\Frontend;

use Livewire\Component;
use Src\Cart\Application\AddItemToCart;
use Src\Cart\Infrastructure\EloquentCartRepository;

class AddToCartButton extends Component
{
    public ?string $productId = null;
    public bool $isLoading = false;
    public string $buttonText;
    public bool $isDisabled = false;
    public bool $isSoldOut = false;
    public bool $isHidden = false;

    public function mount(?string $productId = null, ?string $buttonText = null, bool $isSoldOut = false, bool $isHidden = false)
    {
        $this->productId = $productId;
        $this->buttonText = $buttonText ?? trans('components/cart.add-to-cart');
        $this->isSoldOut = $isSoldOut;
        $this->isHidden = $isHidden;
        $this->isDisabled = empty($this->productId) || $this->isSoldOut || $this->isHidden;
    }

    public function addToCart()
    {
        if (empty($this->productId)) {
            session()->flash('cart-error', trans('components/cart.error-adding'));
            return;
        }

        $this->isLoading = true;

        try {
            $addItemToCart = new AddItemToCart(new EloquentCartRepository());
            
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $addItemToCart($this->productId, 1, $userId, $sessionId);
            
            // Emit event to update cart icon
            $this->dispatch('cartUpdated');
            
            // Show success message
            session()->flash('cart-success', trans('components/cart.item-added'));
            
        } catch (\InvalidArgumentException $e) {
            if (strpos($e->getMessage(), 'already in your cart') !== false) {
                session()->flash('cart-error', app()->getLocale() === 'es' ? 'Este producto ya está en tu carrito' : 'This product is already in your cart');
            } else {
                session()->flash('cart-error', trans('components/cart.error-adding'));
            }
        } catch (\Exception $e) {
            session()->flash('cart-error', trans('components/cart.error-adding'));
        } finally {
            $this->isLoading = false;
        }
    }

    public function addToCartAndGoToCart()
    {
        if (empty($this->productId)) {
            session()->flash('cart-error', trans('components/cart.error-adding'));
            return;
        }

        $this->isLoading = true;

        try {
            $addItemToCart = new AddItemToCart(new EloquentCartRepository());
            
            $userId = auth()->id();
            $sessionId = session()->getId();
            
            $addItemToCart($this->productId, 1, $userId, $sessionId);
            
            // Emit event to update cart icon
            $this->dispatch('cartUpdated');
            
            // Redirect to cart page
            $cartRoute = app()->getLocale() === 'es' ? 'cart.show.es' : 'cart.show.en';
            return redirect()->route($cartRoute, ['locale' => app()->getLocale()]);
            
        } catch (\InvalidArgumentException $e) {
            if (strpos($e->getMessage(), 'already in your cart') !== false) {
                // Product is already in cart, redirect to cart without error
                $cartRoute = app()->getLocale() === 'es' ? 'cart.show.es' : 'cart.show.en';
                return redirect()->route($cartRoute, ['locale' => app()->getLocale()]);
            } else {
                session()->flash('cart-error', trans('components/cart.error-adding'));
            }
        } catch (\Exception $e) {
            session()->flash('cart-error', trans('components/cart.error-adding'));
        } finally {
            $this->isLoading = false;
        }
    }

    public function getDisabledMessage()
    {
        if ($this->isSoldOut) {
            return app()->getLocale() === 'es' ? 'Agotado' : 'Sold Out';
        }
        
        if ($this->isHidden) {
            return app()->getLocale() === 'es' ? 'No disponible' : 'Not Available';
        }
        
        return app()->getLocale() === 'es' ? 'Producto no disponible' : 'Product not available';
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart-button');
    }
}