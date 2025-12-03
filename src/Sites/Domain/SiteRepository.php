<?php

declare(strict_types=1);

namespace Src\Sites\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Sites\Infrastructure\Eloquent\SiteEloquentModel;

interface SiteRepository
{
    public function save(SiteEloquentModel $site): void;

    public function search(string $id): ?SiteEloquentModel;

    public function searchByDomain(string $domain): ?SiteEloquentModel;

    public function searchBySlug(string $slug): ?SiteEloquentModel;

    public function searchByCriteria(Criteria $criteria): array;

    public function findAll(): array;

    public function findActive(): array;
}
