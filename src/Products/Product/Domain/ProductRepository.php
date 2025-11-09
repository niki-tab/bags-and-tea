<?php

declare(strict_types=1);

namespace Src\Products\Product\Domain;


use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

interface ProductRepository
{
    public function save(ProductEloquentModel $product): void;
    public function search(string $id): ?ProductEloquentModel;
    public function searchByCriteria(Criteria $criteria): array;
    public function countByCriteria(Criteria $criteria): int;
    public function countByCriteriaForUser(string $userId, Criteria $criteria): int;
    public function addCategory(string $productId, string $categoryId): void;
    public function addAttribute(string $productId, string $attributeId): void;
}
