<?php

declare(strict_types=1);

namespace Src\Attributes\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;

interface AttributeRepository
{
    public function save(AttributeEloquentModel $attribute): void;
    
    public function search(string $id): ?AttributeEloquentModel;
    
    public function searchByCriteria(Criteria $criteria): array;
    
    public function findAll(): array;
    
    public function findActive(): array;
    
    public function findRoots(): array;
    
    public function findActiveRoots(): array;
    
    public function findByParentName(string $parentName): ?array;
}