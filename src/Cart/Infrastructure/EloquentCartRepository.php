<?php

declare(strict_types=1);

namespace Src\Cart\Infrastructure;

use Src\Cart\Domain\CartRepository;
use Src\Cart\Infrastructure\Eloquent\CartEloquentModel;
use Src\Cart\Infrastructure\Eloquent\CartItemEloquentModel;
use Illuminate\Support\Str;

class EloquentCartRepository implements CartRepository
{
    public function findByUserId(string $userId): ?array
    {
        $cart = CartEloquentModel::with(['items.product'])
            ->forUser($userId)
            ->first();

        return $cart ? $cart->toArray() : null;
    }

    public function findBySessionId(string $sessionId): ?array
    {
        $cart = CartEloquentModel::with(['items.product'])
            ->forSession($sessionId)
            ->first();

        return $cart ? $cart->toArray() : null;
    }

    public function create(array $cartData): array
    {
        $cart = CartEloquentModel::create([
            'user_id' => $cartData['user_id'] ?? null,
            'session_id' => $cartData['session_id'] ?? null,
            'expires_at' => $cartData['expires_at'] ?? null,
        ]);

        return $cart->load(['items.product'])->toArray();
    }

    public function addItem(string $cartId, array $itemData): array
    {
        $existingItem = CartItemEloquentModel::where('cart_id', $cartId)
            ->where('product_id', $itemData['product_id'])
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + ($itemData['quantity'] ?? 1)
            ]);
            $item = $existingItem;
        } else {
            $item = CartItemEloquentModel::create([
                'cart_id' => $cartId,
                'product_id' => $itemData['product_id'],
                'quantity' => $itemData['quantity'] ?? 1,
            ]);
        }

        return $item->load('product')->toArray();
    }

    public function removeItem(string $cartId, string $productId): bool
    {
        return CartItemEloquentModel::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->delete() > 0;
    }

    public function updateItemQuantity(string $cartId, string $productId, int $quantity): array
    {
        $item = CartItemEloquentModel::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();
            return [];
        }

        $item->update(['quantity' => $quantity]);

        return $item->load('product')->toArray();
    }

    public function clearCart(string $cartId): bool
    {
        return CartItemEloquentModel::where('cart_id', $cartId)->delete() >= 0;
    }

    public function getCartWithItems(string $cartId): ?array
    {
        $cart = CartEloquentModel::with(['items.product.brand', 'items.product.primaryImage'])
            ->find($cartId);

        return $cart ? $cart->toArray() : null;
    }

    public function mergeGuestCartWithUserCart(string $sessionId, string $userId): array
    {
        // Find guest cart
        $guestCart = CartEloquentModel::forSession($sessionId)->first();
        
        if (!$guestCart) {
            // No guest cart, just return user cart or create new one
            return $this->findByUserId($userId) ?? $this->create(['user_id' => $userId]);
        }

        // Find or create user cart
        $userCart = CartEloquentModel::forUser($userId)->first();
        
        if (!$userCart) {
            // No user cart exists, convert guest cart to user cart
            $guestCart->update([
                'user_id' => $userId,
                'session_id' => null,
                'expires_at' => null,
            ]);
            
            return $guestCart->load(['items.product'])->toArray();
        }

        // Merge guest cart items into user cart
        foreach ($guestCart->items as $guestItem) {
            $existingUserItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingUserItem) {
                $existingUserItem->update([
                    'quantity' => $existingUserItem->quantity + $guestItem->quantity
                ]);
            } else {
                CartItemEloquentModel::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                ]);
            }
        }

        // Delete guest cart
        $guestCart->delete();

        return $userCart->load(['items.product'])->toArray();
    }

    public function deleteExpiredCarts(): int
    {
        $expiredCarts = CartEloquentModel::expired()->get();
        $count = $expiredCarts->count();
        
        foreach ($expiredCarts as $cart) {
            $cart->delete(); // This will cascade delete items
        }

        return $count;
    }
}