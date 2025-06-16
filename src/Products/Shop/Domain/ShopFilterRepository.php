<?php

declare(strict_types=1);

namespace Src\Products\Shop\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Shop\Infrastructure\Eloquent\ShopFilterEloquentModel;

interface ShopFilterRepository
{
    public function save(ShopFilterEloquentModel $shopFilter): void;
    
    public function search(string $id): ?ShopFilterEloquentModel;
    
    public function searchByCriteria(Criteria $criteria): array;
    
    public function findAll(): array;
    
    public function findActive(): array;
    
    public function findByType(string $type): array;
}