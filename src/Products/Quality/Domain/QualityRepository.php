<?php

declare(strict_types=1);

namespace Src\Products\Quality\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;

interface QualityRepository
{
    public function save(QualityEloquentModel $quality): void;
    
    public function search(string $id): ?QualityEloquentModel;
    
    public function searchByCriteria(Criteria $criteria): array;
    
    public function findAll(): array;
}
