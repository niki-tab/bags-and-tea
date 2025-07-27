<?php

declare(strict_types=1);

namespace Src\Cart\Domain;

interface CartRepository
{
    public function findByUserId(string $userId): ?array;
    
    public function findBySessionId(string $sessionId): ?array;
    
    public function create(array $cartData): array;
    
    public function addItem(string $cartId, array $itemData): array;
    
    public function removeItem(string $cartId, string $productId): bool;
    
    public function updateItemQuantity(string $cartId, string $productId, int $quantity): array;
    
    public function clearCart(string $cartId): bool;
    
    public function getCartWithItems(string $cartId): ?array;
    
    public function mergeGuestCartWithUserCart(string $sessionId, string $userId): array;
    
    public function deleteExpiredCarts(): int;
}