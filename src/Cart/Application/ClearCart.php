<?php

declare(strict_types=1);

namespace Src\Cart\Application;

use Src\Cart\Domain\CartRepository;

final class ClearCart
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function __invoke(?string $userId = null, ?string $sessionId = null): bool
    {
        if (!$userId && !$sessionId) {
            throw new \InvalidArgumentException('Either user ID or session ID is required');
        }

        $cart = $userId 
            ? $this->cartRepository->findByUserId($userId)
            : $this->cartRepository->findBySessionId($sessionId);

        if (!$cart) {
            return false;
        }

        return $this->cartRepository->clearCart($cart['id']);
    }
}