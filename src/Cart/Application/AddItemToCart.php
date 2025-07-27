<?php

declare(strict_types=1);

namespace Src\Cart\Application;

use Src\Cart\Domain\CartRepository;

final class AddItemToCart
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function __invoke(
        string $productId,
        int $quantity = 1,
        ?string $userId = null,
        ?string $sessionId = null
    ): array {
        if (empty($productId)) {
            throw new \InvalidArgumentException('Product ID is required');
        }

        if ($quantity < 1 || $quantity > 999) {
            throw new \InvalidArgumentException('Quantity must be between 1 and 999');
        }

        if (!$userId && !$sessionId) {
            throw new \InvalidArgumentException('Either user ID or session ID is required');
        }

        // Find or create cart
        $cart = $userId 
            ? $this->cartRepository->findByUserId($userId)
            : $this->cartRepository->findBySessionId($sessionId);

        if (!$cart) {
            $cartData = $userId 
                ? ['user_id' => $userId]
                : ['session_id' => $sessionId, 'expires_at' => now()->addDays(30)];
            
            $cart = $this->cartRepository->create($cartData);
        }

        // Check if product is already in cart (for unique items with stock = 1)
        $cartWithItems = $this->cartRepository->getCartWithItems($cart['id']);
        if ($cartWithItems && isset($cartWithItems['items'])) {
            foreach ($cartWithItems['items'] as $item) {
                if ($item['product_id'] === $productId) {
                    throw new \InvalidArgumentException('This product is already in your cart');
                }
            }
        }
        
        $this->cartRepository->addItem($cart['id'], [
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);

        return $this->cartRepository->getCartWithItems($cart['id']);
    }
}