<?php

declare(strict_types=1);

namespace Src\Categories\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;

interface CategoryRepository
{
    public function save(CategoryEloquentModel $category): void;
    
    public function search(string $id): ?CategoryEloquentModel;
    
    public function searchByCriteria(Criteria $criteria): array;
    
    public function findAll(): array;
    
    public function findActive(): array;
    
    public function findRoots(): array;
    
    public function findActiveRoots(): array;
}