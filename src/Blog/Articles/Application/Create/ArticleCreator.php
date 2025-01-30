<?php
declare(strict_types=1);
namespace Src\Blog\Articles\Application\Create;

use Ramsey\Uuid\Uuid;
use Src\Blog\Articles\Model\ArticleModel;


final class ArticleCreator
{
    public function __invoke(
        string $id,
        string $title,
        string $slug,
        string $body,
        string $status,
        string $meta_title,
        string $meta_description,
        string $meta_keywords,
    ): ArticleModel {
        
        // Create a new instance each time the method is called
        $article = new ArticleModel();
        $article->id = $id;
        $article->title = $title;
        $article->slug = $slug;
        $article->body = $body;
        $article->state = $status;
        $article->meta_title = $meta_title;
        $article->meta_description = $meta_description;
        $article->meta_keywords = $meta_keywords;


        $article->save();

        return $article;
    }
}