<?php

declare(strict_types=1);

namespace Src\ThirdPartyServices\Firecrawl\Domain\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class FirecrawlApiException extends Exception
{
    public function __construct(
        private readonly string $webUrl,
    ) {
        parent::__construct($this->errorMessage(), $this->errorCode());
        $this->logException();
    }

    public function errorCode(): int
    {
        return 472;
    }

    public function errorMessage(): string
    {
        return sprintf(
            'There was an API response error (Firecrawl) with the url <%s>',
            $this->webUrl,
        );
    }

    public function errorStatusCode(): int
    {
        return Response::HTTP_CONFLICT;
    }

    private function logException(): void
    {
        $context = [
            'webUrl' => $this->webUrl,
            'error_code' => $this->errorCode(),
            'status_code' => $this->errorStatusCode(),
            'message' => $this->errorMessage(),
        ];

        Log::warning('API response error (Firecrawl)', $context);
    }
}