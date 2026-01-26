<?php

declare(strict_types=1);

namespace Src\Categories\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Categories\Domain\CategoryRepository;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;

final class EloquentCategoryRepository implements CategoryRepository
{
    public function save(CategoryEloquentModel $category): void
    {
        $category->save();
    }

    public function search(string $id): ?CategoryEloquentModel
    {
        return CategoryEloquentModel::find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = CategoryEloquentModel::query();

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
        return CategoryEloquentModel::with('children')->ordered()->get()->all();
    }

    public function findActive(): array
    {
        return CategoryEloquentModel::active()->with('children')->ordered()->get()->all();
    }

    public function findRoots(): array
    {
        return CategoryEloquentModel::roots()->withChildren()->ordered()->get()->all();
    }

    public function findActiveRoots(): array
    {
        return CategoryEloquentModel::active()->roots()->withChildren()->ordered()->get()->all();
    }

    public function findByParentName(string $parentName): ?array
    {
        return CategoryEloquentModel::query()
        ->join('categories as parent', 'categories.parent_id', '=', 'parent.id')
        ->where('parent.name->en', 'like', '%' . $parentName . '%')
        ->where('categories.is_active', true)
        ->select('categories.*')
        ->orderBy('categories.name->en')
        ->get()
        ->all();
    }

    /**
     * Find a category by slug in any locale.
     */
    public function findBySlug(string $slug): ?CategoryEloquentModel
    {
        return CategoryEloquentModel::query()
            ->where(function ($query) use ($slug) {
                $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.en")) = ?', [$slug])
                      ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(slug, "$.es")) = ?', [$slug]);
            })
            ->first();
    }

}