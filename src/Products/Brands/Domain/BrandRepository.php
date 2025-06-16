<?php

declare(strict_types=1);

namespace Src\Products\Brands\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;

interface BrandRepository
{
    public function save(BrandEloquentModel $brand): void;
    
    public function search(string $id): ?BrandEloquentModel;
    
    public function searchByCriteria(Criteria $criteria): array;
    
    public function findAll(): array;
    
    public function findActive(): array;
}