<?php
declare(strict_types=1);
namespace Src\Blog\Articles\Application\Update;

use Ramsey\Uuid\Uuid;
use Src\Blog\Articles\Model\ArticleModel;


final class UpdateById
{
    
    public function __construct()
    {
    }

    public function __invoke(
        ArticleModel $articleModel,
        string $title,
        string $slug,
        string $body,
        string $status,
        string $meta_title,
        string $meta_description,
        string $meta_keywords,
    ): ArticleModel {


        $articleModel->title = $title;
        $articleModel->slug = $slug;
        $articleModel->body = $body;
        $articleModel->state = $status;
        $articleModel->meta_title = $meta_title;
        $articleModel->meta_description = $meta_description;
        $articleModel->meta_keywords = $meta_keywords;

        $articleModel->save();

        return $articleModel;

    }
}