<?php

declare(strict_types=1);

namespace Src\Cart\Application;

use Src\Cart\Domain\CartRepository;

final class RetrieveCartContents
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function __invoke(?string $userId = null, ?string $sessionId = null): array
    {
        if (!$userId && !$sessionId) {
            return ['items' => [], 'total_items' => 0];
        }

        $cart = $userId 
            ? $this->cartRepository->findByUserId($userId)
            : $this->cartRepository->findBySessionId($sessionId);

        if (!$cart) {
            return ['items' => [], 'total_items' => 0];
        }

        $cartWithItems = $this->cartRepository->getCartWithItems($cart['id']);
        
        if (!$cartWithItems) {
            return ['items' => [], 'total_items' => 0];
        }

        $totalItems = array_sum(array_column($cartWithItems['items'], 'quantity'));

        return [
            'cart' => $cartWithItems,
            'items' => $cartWithItems['items'],
            'total_items' => $totalItems,
        ];
    }
}