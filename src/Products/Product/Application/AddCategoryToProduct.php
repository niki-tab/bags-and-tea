<?php

declare(strict_types=1);

namespace Src\Products\Product\Application;

use Src\Products\Product\Domain\ProductRepository;
use Src\Categories\Domain\CategoryRepository;
use InvalidArgumentException;

final class AddCategoryToProduct
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
    ) {}

    public function execute(string $productId, string $categoryId): void
    {
        // Validate that the product exists
        $product = $this->productRepository->search($productId);
        if (!$product) {
            throw new InvalidArgumentException("Product with ID {$productId} not found");
        }

        // Validate that the category exists
        $category = $this->categoryRepository->search($categoryId);
        if (!$category) {
            throw new InvalidArgumentException("Category with ID {$categoryId} not found");
        }

        // Check if the category is already associated with the product
        if ($product->categories()->where('category_id', $categoryId)->exists()) {
            throw new InvalidArgumentException("Category is already associated with this product");
        }

        // Add the category to the product
        $this->productRepository->addCategory($productId, $categoryId);
    }
}