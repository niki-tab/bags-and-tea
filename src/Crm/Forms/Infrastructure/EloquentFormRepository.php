<?php

declare(strict_types=1);

namespace Src\Crm\Forms\Infrastructure;

use Src\Crm\Forms\Domain\FormRepository;
use Src\Crm\Forms\Domain\FormSubmissionModel;
use Src\Crm\Forms\Infrastructure\Eloquent\FormEloquentModel;
use Src\Crm\Forms\Infrastructure\Eloquent\FormSubmissionEloquentModel;

final class EloquentFormRepository implements FormRepository
{
    public function saveFormSubmission(FormSubmissionModel $formSubmission): void
    {
        $formSubmission->save();
    }

    public function retrieveAllForms(): array
    {
        $forms = FormEloquentModel::select('id', 'form_name', 'form_identifier', 'form_description')
            ->where('is_active', true)
            ->withCount('submissions')
            ->orderBy('form_name')
            ->get();

        return $forms->map(function ($form) {
            return [
                'id' => $form->id,
                'form_name' => $form->form_name,
                'form_identifier' => $form->form_identifier,
                'form_description' => $form->form_description,
                'submissions_count' => $form->submissions_count,
            ];
        })->toArray();
    }

    public function retrieveFormSubmissions(string $formId, int $perPage = 15): array
    {
        $submissions = FormSubmissionEloquentModel::where('crm_form_id', $formId)
            ->with('form:id,form_name')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return [
            'submissions' => $submissions->items(),
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'per_page' => $submissions->perPage(),
                'total' => $submissions->total(),
                'has_more_pages' => $submissions->hasMorePages(),
            ]
        ];
    }

    public function retrieveSubmissionDetail(string $submissionId): ?array
    {
        $submission = FormSubmissionEloquentModel::with('form')
            ->find($submissionId);

        if (!$submission) {
            return null;
        }

        return [
            'id' => $submission->id,
            'form_name' => $submission->form->form_name,
            'form_identifier' => $submission->form->form_identifier,
            'form_fields' => $submission->form->form_fields,
            'answers' => $submission->crm_form_answers,
            'submitted_at' => $submission->created_at,
        ];
    }

    public function retrieveFormById(string $formId): ?array
    {
        $form = FormEloquentModel::find($formId);

        if (!$form) {
            return null;
        }

        return [
            'id' => $form->id,
            'form_name' => $form->form_name,
            'form_identifier' => $form->form_identifier,
            'form_description' => $form->form_description,
            'form_fields' => $form->form_fields,
            'is_active' => $form->is_active,
        ];
    }
}
