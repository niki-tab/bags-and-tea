<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Infrastructure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Src\Shared\Infrastructure\Controller\ApiController;
use Illuminate\Support\Facades\Validator; 

class PostCreateArticleController extends ApiController
{
    public function __invoke(Request $request): Response
    { 
        
        $validateRequest = $this->validateRequest($request);

        if(isset($validateRequest["errors"])){
            return $this->errorResponse(Response::HTTP_BAD_REQUEST,400 ,$validateRequest["errors"]);
        }

        $response = "ok";
        return $this->successResponse(Response::HTTP_ACCEPTED, $response);

    }

    private function validateRequest(Request $request): array
    {

        /*$headerRules = [
            'x-user-id' => 'required',
        ];

        $headerData = $request->header();

        $headerValidator = Validator::make($headerData, $headerRules);

        if ($headerValidator->fails()) {
            return ['errors' => $headerValidator->errors()->toArray()];
        }*/

        $data = $request->request->all();

        $rules = [
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