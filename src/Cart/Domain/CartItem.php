<?php

declare(strict_types=1);

namespace Src\Cart\Domain;

final class CartItem
{
    public function __construct(
        private string $id,
        private string $cartId,
        private string $productId,
        private int $quantity,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt
    ) {
        $this->validateQuantity($quantity);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateQuantity(int $quantity): self
    {
        $this->validateQuantity($quantity);
        
        return new self(
            $this->id,
            $this->cartId,
            $this->productId,
            $quantity,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function increaseQuantity(int $amount = 1): self
    {
        return $this->updateQuantity($this->quantity + $amount);
    }

    private function validateQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Cart item quantity must be at least 1');
        }

        if ($quantity > 999) {
            throw new \InvalidArgumentException('Cart item quantity cannot exceed 999');
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'cart_id' => $this->cartId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}