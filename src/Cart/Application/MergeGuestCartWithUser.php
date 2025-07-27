<?php

declare(strict_types=1);

namespace Src\Cart\Application;

use Src\Cart\Domain\CartRepository;

final class MergeGuestCartWithUser
{
    public function __construct(
        private CartRepository $cartRepository
    ) {}

    public function __invoke(string $sessionId, string $userId): array
    {
        if (empty($sessionId) || empty($userId)) {
            throw new \InvalidArgumentException('Session ID and User ID are required');
        }

        return $this->cartRepository->mergeGuestCartWithUserCart($sessionId, $userId);
    }
}