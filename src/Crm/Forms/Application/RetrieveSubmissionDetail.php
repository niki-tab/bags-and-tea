<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Application;

use Src\Crm\Forms\Domain\FormRepository;

final class RetrieveSubmissionDetail
{
    public function __construct(
        private FormRepository $formRepository
    ) {}

    public function __invoke(string $submissionId): ?array
    {
        if (empty($submissionId)) {
            throw new \InvalidArgumentException('Submission ID is required');
        }

        return $this->formRepository->retrieveSubmissionDetail($submissionId);
    }
}