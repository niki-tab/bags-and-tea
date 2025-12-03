<?php

declare(strict_types=1);

namespace Src\Sites\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Sites\Domain\SiteRepository;
use Src\Sites\Infrastructure\Eloquent\SiteEloquentModel;

final class EloquentSiteRepository implements SiteRepository
{
    public function save(SiteEloquentModel $site): void
    {
        $site->save();
    }

    public function search(string $id): ?SiteEloquentModel
    {
        return SiteEloquentModel::find($id);
    }

    public function searchByDomain(string $domain): ?SiteEloquentModel
    {
        return SiteEloquentModel::byDomain($domain)->active()->first();
    }

    public function searchBySlug(string $slug): ?SiteEloquentModel
    {
        return SiteEloquentModel::bySlug($slug)->first();
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = SiteEloquentModel::query();

        foreach ($criteria->plainFilters() as $filter) {
            $value = $filter->value()->value();
            if ($filter->operator()->value() === 'like') {
                $value = '%' . $value . '%';
            }

            $query->where(
                $filter->field()->value(),
                $filter->operator()->value(),
                $value
            );
        }

        $limit = $criteria->limit();
        if ($limit === 0 || !isset($limit)) {
            $limit = 50;
        }

        return $query->offset($criteria->offset() ?? 0)
            ->limit($limit)
            ->get()
            ->all();
    }

    public function findAll(): array
    {
        return SiteEloquentModel::all()->all();
    }

    public function findActive(): array
    {
        return SiteEloquentModel::active()->get()->all();
    }
}
