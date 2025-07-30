<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Domain;

interface FormRepository
{
    public function saveFormSubmission(FormSubmissionModel $formSubmission): void;
    
    public function retrieveAllForms(): array;
    
    public function retrieveFormSubmissions(string $formId, int $perPage = 15): array;
    
    public function retrieveSubmissionDetail(string $submissionId): ?array;
    
    public function retrieveFormById(string $formId): ?array;
}
