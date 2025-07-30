<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Application;

use Src\Crm\Forms\Domain\FormRepository;

final class RetrieveFormSubmissions
{
    public function __construct(
        private FormRepository $formRepository
    ) {}

    public function __invoke(string $formId, int $perPage = 15): array
    {
        if (empty($formId)) {
            throw new \InvalidArgumentException('Form ID is required');
        }

        return $this->formRepository->retrieveFormSubmissions($formId, $perPage);
    }
}