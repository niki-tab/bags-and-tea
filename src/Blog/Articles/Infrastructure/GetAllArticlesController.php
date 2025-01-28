<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Infrastructure;

use Src\Blog\Articles\Model\ArticleModel;
use Symfony\Component\HttpFoundation\Response;
use Src\Shared\Infrastructure\Controller\ApiController;

//class GetAllArticlesController extends ApiController
class GetAllArticlesController extends ApiController
{
    public function __invoke():Response
    {   
        
        $allArticles = ArticleModel::all();

        $response = $allArticles;

        return $this->successResponse(Response::HTTP_ACCEPTED, $response);

    }
}