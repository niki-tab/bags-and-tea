<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ErrorJsonResponse
{
    public static function create($statusCode, $result = null, $header = [], $json = false)
    {
        return new JsonResponse(
            [
                'status' => 'error',
                'result' => $result
            ],
            $statusCode,
            $header,
            $json
        );
    }
}
