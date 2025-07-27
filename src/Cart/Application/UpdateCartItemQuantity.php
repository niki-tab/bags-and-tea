<?php

declare(strict_types=1);

namespace Src\Cart\Application;

use Src\Cart\Domain\CartRepository;

final class UpdateCartItemQuantity
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function __invoke(
        string $productId,
        int $quantity,
        ?string $userId = null,
        ?string $sessionId = null
    ): array {
        if (empty($productId)) {
            throw new \InvalidArgumentException('Product ID is required');
        }

        if ($quantity < 0 || $quantity > 999) {
            throw new \InvalidArgumentException('Quantity must be between 0 and 999');
        }

        if (!$userId && !$sessionId) {
            throw new \InvalidArgumentException('Either user ID or session ID is required');
        }

        $cart = $userId 
            ? $this->cartRepository->findByUserId($userId)
            : $this->cartRepository->findBySessionId($sessionId);
        
        if (!$cart) {
            throw new \InvalidArgumentException('Cart not found');
        }

        $this->cartRepository->updateItemQuantity($cart['id'], $productId, $quantity);

        return $this->cartRepository->getCartWithItems($cart['id']) ?? ['items' => []];
    }
}