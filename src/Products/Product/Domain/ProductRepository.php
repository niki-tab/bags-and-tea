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

}
