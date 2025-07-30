<?php

declare(strict_types=1);

namespace Src\Admin\Crm\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Crm\Forms\Application\RetrieveFormsList;
use Src\Crm\Forms\Application\RetrieveFormSubmissions;
use Src\Crm\Forms\Infrastructure\EloquentFormRepository;

class ShowAllFormSubmissions extends Component
{
    use WithPagination;

    public array $forms = [];
    public string $selectedFormId = '';
    public array $submissions = [];
    public array $paginationData = [];
    public bool $isLoading = false;

    public function mount()
    {
        $this->loadForms();
    }

    public function loadForms()
    {
        $retrieveFormsList = new RetrieveFormsList(new EloquentFormRepository());
        $this->forms = $retrieveFormsList();
        
        // Auto-select first form if available and no form is selected
        if (empty($this->selectedFormId) && !empty($this->forms)) {
            $this->selectedFormId = $this->forms[0]['id'];
            $this->loadSubmissions();
        }
    }

    public function updatedSelectedFormId()
    {
        if (!empty($this->selectedFormId)) {
            $this->resetPage();
            $this->loadSubmissions();
        }
    }

    public function loadSubmissions()
    {
        if (empty($this->selectedFormId)) {
            $this->submissions = [];
            $this->paginationData = [];
            return;
        }

        $this->isLoading = true;

        try {
            $retrieveFormSubmissions = new RetrieveFormSubmissions(new EloquentFormRepository());
            $result = $retrieveFormSubmissions($this->selectedFormId, 15);
            
            $this->submissions = collect($result['submissions'])->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'form_name' => $submission->form->form_name,
                    'answers' => $submission->crm_form_answers,
                    'submitted_at' => $submission->created_at->format('Y-m-d H:i:s'),
                    'preview' => $this->generatePreview($submission->crm_form_answers),
                ];
            })->toArray();
            
            $this->paginationData = $result['pagination'];
        } catch (\Exception $e) {
            $this->submissions = [];
            $this->paginationData = [];
            session()->flash('error', 'Error loading submissions: ' . $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    private function generatePreview(array $answers): string
    {
        if (empty($answers)) {
            return 'No answers available';
        }

        // Get first few non-empty answers for preview
        $previewParts = [];
        $count = 0;
        
        foreach ($answers as $key => $value) {
            if ($count >= 3) break;
            
            if (!empty($value) && is_string($value)) {
                $shortValue = strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value;
                $previewParts[] = ucfirst($key) . ': ' . $shortValue;
                $count++;
            }
        }

        return empty($previewParts) ? 'No preview available' : implode(' | ', $previewParts);
    }

    public function getSelectedFormName(): string
    {
        if (empty($this->selectedFormId)) {
            return '';
        }

        $selectedForm = collect($this->forms)->firstWhere('id', $this->selectedFormId);
        return $selectedForm ? $selectedForm['form_name'] : '';
    }

    public function render()
    {
        return view('livewire.admin.crm.show-all-form-submissions')
            ->extends('layouts.admin.app')
            ->section('content');
    }
}