<?php

namespace Src\Crm\Forms\Frontend;

use Livewire\Component;
use App\Models\ProductModel;
use Src\Crm\Forms\Domain\FormModel;
use App\Models\ProductSizeVariationModel;
use Src\Blog\Articles\Model\ArticleModel;
use App\Models\ProductQuantityVariationModel;
use Src\Crm\Forms\Application\UseCases\FormSubmissionCreator;
use App\Models\ProducSizeVariationQuantityVariationPriceModel;

class Form extends Component
{

    public $formTitle;
    public $formIdentifier;
    public $formFields;
    public $formButtonText;
    public $formData = [];
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
            $fieldName = trans($field['name']);
            $messages["formData.{$fieldName}.required"] = trans('components/form-show.validation-required');
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
                    $rules[$fieldName] = 'required';
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
        $this->formFields = $form->form_fields;
        $this->formButtonText = $formButtonText;
        $this->isTermsAndConditions = $isTermsAndConditions;
        $this->isReceiveComercialInformation = $isReceiveComercialInformation;
    }

    public function submit()
    {
        $this->validate();
        $this->formSubmissionCreator->__invoke(
            "88cba589-9abd-44a1-bb87-c1fe6fb8e5b0",
            $this->formData
        );
        //dd($this->formData);
    }

    public function render()
    {
        return view('livewire.crm/forms/show');
    }

    

}
