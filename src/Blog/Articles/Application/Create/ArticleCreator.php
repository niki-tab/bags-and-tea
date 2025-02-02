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
        string $language,
    ): ArticleModel {
        
        // Create a new instance each time the method is called
        $article = new ArticleModel();
        
        // Use setTranslation to set translatable fields
        $article->setTranslation('title', $language, $title);
        $article->setTranslation('slug', $language, $slug);
        $article->setTranslation('body', $language, $body);
        $article->setTranslation('meta_title', $language, $meta_title);
        $article->setTranslation('meta_description', $language, $meta_description);
        $article->setTranslation('meta_keywords', $language, $meta_keywords);

        // Non-translatable fields
        $article->id = $id;
        $article->state = $status;


        $article->save();

        return $article;
    }
}