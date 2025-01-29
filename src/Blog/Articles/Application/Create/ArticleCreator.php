<?php
declare(strict_types=1);
namespace Src\Projects\Application\Create;

use Ramsey\Uuid\Uuid;
use Src\Blog\Articles\Model\ArticleModel;


final class ArticleCreator
{
    
    public function __construct(private ArticleModel $articleModel)
    {
    }

    public function __invoke(
        $title,
        $slug,
        $body,
    ): ArticleModel {


        $this->articleModel->title = $title;
        $this->articleModel->slug = $slug;
        $this->articleModel->body = $body;


        $this->articleModel->save();

        return $this->articleModel;

    }
}