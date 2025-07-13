<?php

declare(strict_types=1);

namespace Src\Attributes\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Attributes\Domain\AttributeRepository;
use Src\Attributes\Infrastructure\Eloquent\AttributeEloquentModel;

final class EloquentAttributeRepository implements AttributeRepository
{
    public function save(AttributeEloquentModel $attribute): void
    {
        $attribute->save();
    }

    public function search(string $id): ?AttributeEloquentModel
    {
        return AttributeEloquentModel::find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = AttributeEloquentModel::query();

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
        return AttributeEloquentModel::with('children')->ordered()->get()->all();
    }

    public function findActive(): array
    {
        return AttributeEloquentModel::active()->with('children')->ordered()->get()->all();
    }

    public function findRoots(): array
    {
        return AttributeEloquentModel::roots()->withChildren()->ordered()->get()->all();
    }

    public function findActiveRoots(): array
    {
        return AttributeEloquentModel::active()->roots()->withChildren()->ordered()->get()->all();
    }

    public function findByParentName(string $parentName): ?array
    {
        return AttributeEloquentModel::query()
        ->join('attributes as parent', 'attributes.parent_id', '=', 'parent.id')
        ->where('parent.name->en', 'like', '%' . $parentName . '%')
        ->where('attributes.is_active', true)
        ->select('attributes.*')
        ->orderBy('attributes.name->en')
        ->get()
        ->all();
    }
}