<?php

declare(strict_types=1);

namespace Src\Vendors\Domain;

use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;

interface VendorRepository
{
    public function save(VendorEloquentModel $vendor): void;
    
    public function search(string $id): ?VendorEloquentModel;
    
    public function findByUserId(string $userId): ?VendorEloquentModel;
    
    public function findActive(): array;
    
    public function findAll(): array;
    
    public function delete(string $id): void;
}