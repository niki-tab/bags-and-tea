<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Application;

use Src\Crm\Forms\Domain\FormRepository;

final class RetrieveFormsList
{
    public function __construct(
        private FormRepository $formRepository
    ) {}

    public function __invoke(): array
    {
        return $this->formRepository->retrieveAllForms();
    }
}