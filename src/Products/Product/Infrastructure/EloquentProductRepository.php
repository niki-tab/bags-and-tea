<?php

declare(strict_types=1);

namespace Src\Products\Product\Infrastructure;

use ChannelManager\Shared\Domain\Criteria\Criteria;
use ChannelManager\Core\Tours\Domain\TourRepository;
use ChannelManager\Core\Tours\Infrastructure\Eloquent\TourEloquentModel;

final class EloquentProductRepository implements ProductRepository
{
    public function save(TourEloquentModel $tour): void
    {
        $tour->save();
    }
    public function search(string $id): ?TourEloquentModel
    {
        return TourEloquentModel::find($id);
    }
    public function searchByCriteria(Criteria $criteria): array
    {
        $whereClause = [];

        foreach ($criteria->plainFilters() as $filter) {
            $value = $filter->value()->value();
            if ($filter->operator()->value() === 'like') {
                $value = '%' . $value . '%';
            }

            array_push($whereClause, [
                $filter->field()->value(),
                $filter->operator()->value(),
                $value,
            ]);
        }

        $limit = $criteria->limit();
        if ($limit === 0 || !isset($limit)) {
            $limit = 50;
        }

        $eloquentModels = TourEloquentModel::where($whereClause)
            ->offset($criteria->offset() ?? 0)
            ->limit($limit)
            ->get();

        if (null === $eloquentModels) {
            return [];
        }
        return $eloquentModels->all();
    }
}
