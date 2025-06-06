<?php

declare(strict_types=1);

namespace Src\Products\Quality\Domain;


use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

interface QualityRepository
{
    public function save(ProductEloquentModel $product): void;
    public function search(string $id): ?ProductEloquentModel;
    public function searchByCriteria(Criteria $criteria): array;

}
