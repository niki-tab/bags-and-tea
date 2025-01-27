<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Src\Shared\Infrastructure\Responses\ErrorJsonResponse;
use Src\Shared\Infrastructure\Responses\SuccessJsonResponse;

abstract class ApiController
{
    public function __construct(
    ) {
    }

    public function successResponse(int $httpCode = 200, mixed $data = null): JsonResponse
    {
        return SuccessJsonResponse::create(
            $httpCode,
            $data,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    public function errorResponse(int $httpCode, int $errorCode = 0, mixed $errorMessage = null): JsonResponse
    {
        return ErrorJsonResponse::create(
            $httpCode,
            [
                'errorCode' => $errorCode,
                'errorMessage' => $errorMessage
            ],
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}
