<?php

declare(strict_types=1);

namespace Src\Cart\Application;

use Src\Cart\Domain\CartRepository;

final class RemoveItemFromCart
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function __invoke(
        string $productId,
        ?string $userId = null,
        ?string $sessionId = null
    ): array {
        if (empty($productId)) {
            throw new \InvalidArgumentException('Product ID is required');
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

        $this->cartRepository->removeItem($cart['id'], $productId);

        return $this->cartRepository->getCartWithItems($cart['id']) ?? ['items' => []];
    }
}