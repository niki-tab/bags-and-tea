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
        string $mainImage,
        string $body,
        string $status,
        string $meta_title,
        string $meta_description,
        string $meta_keywords,
        string $language,
    ): ArticleModel {


        // Use setTranslation to set translatable fields
        $articleModel->setTranslation('title', $language, $title);
        $articleModel->setTranslation('slug', $language, $slug);
        $articleModel->setTranslation('body', $language, $body);
        $articleModel->setTranslation('meta_title', $language, $meta_title);
        $articleModel->setTranslation('meta_description', $language, $meta_description);
        $articleModel->setTranslation('meta_keywords', $language, $meta_keywords);

        // Non-translatable fields
        
        if($status == "live"){
            $status == "published";
        }

        $articleModel->state = $status;
        $articleModel->main_image = $mainImage;

        $articleModel->save();

        return $articleModel;

    }
}