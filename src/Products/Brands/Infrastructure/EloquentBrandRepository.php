<?php

declare(strict_types=1);

namespace Src\Products\Brands\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Brands\Domain\BrandRepository;
use Src\Products\Brands\Infrastructure\Eloquent\BrandEloquentModel;

final class EloquentBrandRepository implements BrandRepository
{
    public function save(BrandEloquentModel $brand): void
    {
        $brand->save();
    }

    public function search(string $id): ?BrandEloquentModel
    {
        return BrandEloquentModel::find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = BrandEloquentModel::query();

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
        return BrandEloquentModel::ordered()->get()->all();
    }

    public function findActive(): array
    {
        return BrandEloquentModel::active()->ordered()->get()->all();
    }
}