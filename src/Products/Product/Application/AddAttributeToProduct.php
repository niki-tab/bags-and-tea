<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use InvalidArgumentException;
use Src\Attributes\Domain\AttributeRepository;
use Src\Products\Product\Domain\ProductRepository;

final class AddAttributeToProduct
{
    public function __construct(
        private ProductRepository $productRepository,
        private AttributeRepository $attributeRepository
    ) {}

    public function execute(string $productId, string $attributeId): void
    {
        // Validate that the product exists
        $product = $this->productRepository->search($productId);
        if (!$product) {
            throw new InvalidArgumentException("Product with ID {$productId} not found");
        }

        // Validate that the category exists
        $attribute = $this->attributeRepository->search($attributeId);
        if (!$attribute) {
            throw new InvalidArgumentException("Attribute with ID {$attributeId} not found");
        }

        // Check if the attribute is already associated with the product
        if ($product->attributes()->where('attribute_id', $attributeId)->exists()) {
            throw new InvalidArgumentException("Attribute is already associated with this product");
        }

        // Add the attribute to the product
        $this->productRepository->addAttribute($productId, $attributeId);
    }
}