<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Domain;

use Src\Shared\Domain\Criteria\Criteria;
use Src\Blog\Articles\Model\ArticleModel;

interface ArticleRepository
{
    public function save(ArticleModel $article): void;
    public function search(string $id): ?ArticleModel;
    public function findById(string $id): ?ArticleModel;
    public function searchByCriteria(Criteria $criteria): array;
}