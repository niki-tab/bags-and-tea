<?php

namespace Src\Crm\Forms\Frontend;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\ProductModel;
use Livewire\WithFileUploads; 
use Src\Crm\Forms\Domain\FormModel;
use App\Models\ProductSizeVariationModel;
use Src\Blog\Articles\Model\ArticleModel;
use App\Models\ProductQuantityVariationModel;
use Src\Crm\Forms\Application\UseCases\FormSubmissionCreator;
use App\Models\ProducSizeVariationQuantityVariationPriceModel;

class Form extends Component
{
    use WithFileUploads;
    public $formTitle;
    public $crmFormId;
    public $formIdentifier;
    public $formFields;
    public $formButtonText;
    public $formData = [];
    public $files = [];
    public $isTermsAndConditions;
    public $isReceiveComercialInformation;
    private FormSubmissionCreator $formSubmissionCreator;
    public function boot(FormSubmissionCreator $formSubmissionCreator)
    {
        $this->formSubmissionCreator = $formSubmissionCreator;
    }
    protected function messages()
    {
        $messages = [];
        foreach ($this->formFields as $field) {
            if ($field['type'] === 'file') {
                $fieldName = trans($field['name']);
                $messages["files.{$fieldName}.required"] = trans('components/form-show.validation-required');
            } else {
                $fieldName = trans($field['name']);
                $messages["formData.{$fieldName}.required"] = trans('components/form-show.validation-required');
            }
        }
        $messages["formData.termsAndConditions.required"] = trans('components/form-show.validation-required');
        $messages["formData.receiveComercialInformation.required"] = trans('components/form-show.validation-required');
        return $messages;
    }
    protected function rules()
    {
        $rules = [];
        

        foreach ($this->formFields as $field) {
            $fieldName = "formData.{$field['name']}";
            
            // Basic rules based on field type
            switch ($field['required']) {
                
                case true:
                    if ($field['type'] === 'file') {
                        $fieldName = "files.{$field['name']}";
                        $rules[$fieldName] = 'required|max:2048';
                    } else {
                        $rules[$fieldName] = 'required';
                    }
                    break;

                default:
                    $rules[$fieldName] = 'nullable';
                    break;
            }
        }

        if ($this->isTermsAndConditions === true) {
            $rules['formData.termsAndConditions'] = 'required';
        }

        if ($this->isReceiveComercialInformation === true) {
            $rules['formData.receiveComercialInformation'] = 'required';
        }
        
        return $rules;
    }
    public function mount($formTitle, $formIdentifier, $formButtonText, $isTermsAndConditions = false, $isReceiveComercialInformation = false)
    {   
        $this->formTitle = $formTitle;
        $form = FormModel::where('form_identifier', $formIdentifier)->first();
        $this->crmFormId = $form->id;
        $this->formFields = $form->form_fields;
        $this->formButtonText = $formButtonText;
        $this->isTermsAndConditions = $isTermsAndConditions;
        $this->isReceiveComercialInformation = $isReceiveComercialInformation;
    }

    public function submit()
    {   

        $this->validate();

        foreach ($this->files as $fieldName => $file) {
            if ($file) {
                if (is_array($file)) {
                    // Handle multiple files
                    foreach ($file as $singleFile) {
                        $path = $singleFile->store('form-submissions', 'public');
                        $this->formData[$fieldName][] = $path;
                    }
                } else {
                    // Handle single file
                    $path = $file->store('form-submissions', 'public');
                    $this->formData[$fieldName] = $path;
                }
            }
        }

        $this->formSubmissionCreator->__invoke(
            $this->crmFormId,
            $this->formData
        );

        // Reset form
        $this->formData = [];
        $this->files = [];
        //dd($this->formData);
    }

    public function render()
    {
        return view('livewire.crm/forms/show');
    }

    

}
