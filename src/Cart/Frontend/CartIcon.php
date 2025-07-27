<?php

namespace Src\Cart\Frontend;

use Livewire\Component;
use Src\Cart\Application\RetrieveCartContents;
use Src\Cart\Infrastructure\EloquentCartRepository;

class CartIcon extends Component
{
    public int $totalItems = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function updateCartCount()
    {
        $retrieveCartContents = new RetrieveCartContents(new EloquentCartRepository());
        
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        $cartData = $retrieveCartContents($userId, $sessionId);
        $this->totalItems = $cartData['total_items'];
    }

    public function render()
    {
        return view('livewire.cart.cart-icon');
    }
}