<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Infrastructure;

use Illuminate\Http\Request;
use Src\Blog\Articles\Model\ArticleModel;
use Illuminate\Support\Facades\Validator; 
use Symfony\Component\HttpFoundation\Response;

use Src\Blog\Articles\Application\Update\UpdateById;
use Src\Shared\Infrastructure\Controller\ApiController;
use Src\Blog\Articles\Application\Create\ArticleCreator;

class PostCreateOrUpdateArticleController extends ApiController
{
    public function __invoke(Request $request): Response
    { 
        
        $validateRequest = $this->validateRequest($request);

        if(isset($validateRequest["errors"])){
            return $this->errorResponse(Response::HTTP_BAD_REQUEST,400 ,$validateRequest["errors"]);
        }

        $language = $request->header('x-lang') ?? 'es';

        $articleId = (string) request()->id;
        $articletitle = (string) request()->title;
        $articleslug = (string) request()->slug;
        $articlebody = (string) request()->body;
        $articleStatus = (string) request()->status ?? "draft";
        $articleMetaTitle = (string) request()->metaTitle ?? "";
        $articleMetaDescription = (string) request()->metaDescription ?? "";
        $articleMetaKeywords = (string) request()->metaKeywords ?? "";

        $article = ArticleModel::where("id", $articleId)->first();
        
        if($article){

            $updateArticleByIdApplication = app()->make(UpdateById::class);
            $articleModel = $updateArticleByIdApplication->__invoke(
                $article,
                $articletitle,
                $articleslug,
                $articlebody,
                $articleStatus,
                $articleMetaTitle,
                $articleMetaDescription,
                $articleMetaKeywords,
                $language,
            );

        }else{

            $createArticleApplication = app()->make(ArticleCreator::class);
            $articleModel = $createArticleApplication->__invoke(
                $articleId,
                $articletitle,
                $articleslug,
                $articlebody,
                $articleStatus,
                $articleMetaTitle,
                $articleMetaDescription,
                $articleMetaKeywords,
                $language,
            );
            
        }

        return $this->successResponse(Response::HTTP_ACCEPTED, $articleModel);

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

        $data = $request->request->all();

        $rules = [
            'id' => 'required|string',
            'title' => 'required|string',
            'slug' => 'required|string',
            'body' => 'required|string',
        ];

        $bodyValidator = Validator::make($data, $rules);


        if ($bodyValidator->fails()) {
           
            return ['errors' => $bodyValidator->errors()->toArray()];
        }

        return $bodyValidator->validated();
    }
}