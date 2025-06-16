<?php

declare(strict_types=1);

namespace Src\Products\Shop\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Shop\Domain\ShopFilterRepository;
use Src\Products\Shop\Infrastructure\Eloquent\ShopFilterEloquentModel;

final class EloquentShopFilterRepository implements ShopFilterRepository
{
    public function save(ShopFilterEloquentModel $shopFilter): void
    {
        $shopFilter->save();
    }

    public function search(string $id): ?ShopFilterEloquentModel
    {
        return ShopFilterEloquentModel::find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = ShopFilterEloquentModel::query();

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
        return ShopFilterEloquentModel::ordered()->get()->all();
    }

    public function findActive(): array
    {
        return ShopFilterEloquentModel::active()->ordered()->get()->all();
    }

    public function findByType(string $type): array
    {
        return ShopFilterEloquentModel::active()->byType($type)->ordered()->get()->all();
    }
}