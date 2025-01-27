<?php

declare(strict_types=1);

namespace Src\Blog\Articles\Infrastructure;


use Symfony\Component\HttpFoundation\Response;
use Src\Shared\Infrastructure\Controller\ApiController;

class GetAllArticlesController extends ApiController
{
    public function __invoke():Response
    {   
        
        //$project = ProjectModel::where("id", $projectId)->first();
        
        //$response = $order->load('meta','suborders', 'suborders.items', 'suborders.items.product.media');

        //$response = $project->load('user','products.media');
$response = 1;
        return $this->successResponse(Response::HTTP_ACCEPTED, $response);

    }
}