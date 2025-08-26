<?php

declare(strict_types=1);

namespace Src\Admin\Crm\Frontend;

use Livewire\Component;
use Src\Crm\Forms\Application\RetrieveSubmissionDetail;
use Src\Crm\Forms\Infrastructure\EloquentFormRepository;

class ShowSubmissionDetail extends Component
{
    public string $submissionId;
    public array $submission = [];
    public bool $isLoading = true;
    public bool $notFound = false;

    public function mount(string $submissionId)
    {
        $this->submissionId = $submissionId;
        $this->loadSubmission();
    }

    public function loadSubmission()
    {
        $this->isLoading = true;
        $this->notFound = false;

        try {
            $retrieveSubmissionDetail = new RetrieveSubmissionDetail(new EloquentFormRepository());
            $result = $retrieveSubmissionDetail($this->submissionId);
            
            if ($result) {
                $this->submission = $result;
            } else {
                $this->notFound = true;
            }
        } catch (\Exception $e) {
            $this->notFound = true;
            session()->flash('error', 'Error loading submission: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    public function getFormattedAnswers(): array
    {
        if (empty($this->submission['answers'])) {
            return [];
        }

        $formFields = $this->submission['form_fields'] ?? [];
        $answers = $this->submission['answers'];
        $formattedAnswers = [];

        // If we have form field definitions, use them for better formatting
        if (is_array($formFields)) {
            foreach ($formFields as $field) {
                $fieldName = $field['name'] ?? $field['id'] ?? '';
                $fieldLabel = isset($field['label']) ? trans($field['label']) : ucfirst(str_replace('_', ' ', $fieldName));
                $fieldType = $field['type'] ?? 'text';
                
                if (isset($answers[$fieldName])) {
                    $formattedAnswers[] = [
                        'label' => $fieldLabel,
                        'value' => $answers[$fieldName],
                        'type' => $fieldType,
                    ];
                }
            }
        } else {
            // Fallback: just show all answers with basic formatting
            foreach ($answers as $key => $value) {
                $formattedAnswers[] = [
                    'label' => trans($key),
                    'value' => $value,
                    'type' => 'text',
                ];
            }
        }

        return $formattedAnswers;
    }

    public function formatValue($value, $type = 'text'): string
    {
        if (is_null($value) || $value === '') {
            return 'Not provided';
        }

        if (is_array($value)) {
            return implode(', ', $value);
        }

        switch ($type) {
            case 'email':
                return "<a href='mailto:{$value}' class='text-blue-600 hover:text-blue-800'>{$value}</a>";
            case 'phone':
                return "<a href='tel:{$value}' class='text-blue-600 hover:text-blue-800'>{$value}</a>";
            case 'url':
                return "<a href='{$value}' target='_blank' class='text-blue-600 hover:text-blue-800'>{$value}</a>";
            case 'textarea':
                return nl2br(e($value));
            default:
                return e($value);
        }
    }

    public function render()
    {
        return view('livewire.admin.crm.show-submission-detail')
            ->extends('layouts.admin.app')
            ->section('content');
    }
}