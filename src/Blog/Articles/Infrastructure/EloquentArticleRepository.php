<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Infrastructure;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Blog\Articles\Domain\ArticleRepository;
use Src\Blog\Articles\Model\ArticleModel;

final class EloquentArticleRepository implements ArticleRepository
{
    public function save(ArticleModel $article): void
    {
        $article->save();
    }

    public function search(string $id): ?ArticleModel
    {
        return ArticleModel::find($id);
    }

    public function findById(string $id): ?ArticleModel
    {
        return ArticleModel::find($id);
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        $query = ArticleModel::query();

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