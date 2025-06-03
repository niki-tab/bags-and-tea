<?php

declare(strict_types=1);

namespace Src\Products\Product\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Product\Domain\ProductRepository;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;

final class EloquentProductRepository implements ProductRepository
{
    public function save(ProductEloquentModel $product): void
    {
        $product->save();
    }
    public function search(string $id): ?ProductEloquentModel
    {
        return ProductEloquentModel::find($id);
    }
    public function searchByCriteria(Criteria $criteria): array
    {
        $query = ProductEloquentModel::query();

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
}
