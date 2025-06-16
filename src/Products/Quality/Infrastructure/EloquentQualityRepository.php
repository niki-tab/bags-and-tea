<?php

declare(strict_types=1);

namespace Src\Products\Quality\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Products\Quality\Domain\QualityRepository;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;

final class EloquentQualityRepository implements QualityRepository
{
    public function save(QualityEloquentModel $quality): void
    {
        $quality->save();
    }

    public function search(string $id): ?QualityEloquentModel
    {
        return QualityEloquentModel::find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = QualityEloquentModel::query();

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
        return QualityEloquentModel::all()->toArray();
    }
}