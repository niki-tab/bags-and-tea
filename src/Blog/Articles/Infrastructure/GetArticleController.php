<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Infrastructure;

use Illuminate\Http\Request;
use Src\Blog\Articles\Model\ArticleModel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator; 
use Src\Shared\Infrastructure\Controller\ApiController;

//class GetAllArticlesController extends ApiController
class GetArticleController extends ApiController
{
    public function __invoke(Request $request, $articleId): Response
    {   
        
        $validateRequest = $this->validateRequest($request);

        if(isset($validateRequest["errors"])){
            return $this->errorResponse(Response::HTTP_BAD_REQUEST,400 ,$validateRequest["errors"]);
        }

        $language = $request->header('x-lang') ?? 'es';

        $article = ArticleModel::where('id', $articleId)->first()->getTranslatedAttributes($language);

        $response = $article;

        return $this->successResponse(Response::HTTP_ACCEPTED, $response);

    }

    private function validateRequest(Request $request): array
    {

        $headerRules = [
            'x-lang' => 'required',
        ];

        $headerData = $request->header();

        $headerValidator = Validator::make($headerData, $headerRules);

        if ($headerValidator->fails()) {
            return ['errors' => $headerValidator->errors()->toArray()];
        }

        return $headerValidator->validated();

    }
}