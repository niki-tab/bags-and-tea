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
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

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
    public $showSuccessMessage = false;
    public $isTermsAndConditions;
    
    private FormSubmissionCreator $formSubmissionCreator;
    public function boot(FormSubmissionCreator $formSubmissionCreator)
    {
        $this->formSubmissionCreator = $formSubmissionCreator;
    }
    protected function messages()
    {
        $messages = [];
        foreach ($this->formFields as $field) {
            if ($field['type'] === 'section-title') {
                continue;
            }
            if ($field['type'] === 'file') {
                $fieldName = $field['name'];
                $messages["files.{$fieldName}.required"] = trans('components/form-show.validation-required');
                $messages["files.{$fieldName}.file"] = trans('components/form-show.validation-required');
            } else {
                $fieldName = trans($field['name']);
                $messages["formData.{$fieldName}.required"] = trans('components/form-show.validation-required');
                if ($field['type'] === 'tel') {
                    $messages["formData.{$fieldName}.regex"] = 'El teléfono debe contener solo números, espacios, guiones y paréntesis (7-20 caracteres)';
                }
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
            if ($field['type'] === 'section-title') {
                continue;
            }
            $fieldName = "formData.{$field['name']}";
            
            // Basic rules based on field type
            switch ($field['required']) {
                
                case true:
                    if ($field['type'] === 'file') {
                        $fieldName = "files.{$field['name']}";
                        //$rules[$fieldName] = 'required|array';
                        $rules[$fieldName . '.*'] = 'file|image|mimes:jpeg,jpg,png,gif,webp|max:10240';
                    } elseif ($field['type'] === 'tel') {
                        $rules[$fieldName] = 'required|regex:/^[+]?[0-9\s\-\(\)]{7,20}$/';
                    } else {
                        $rules[$fieldName] = 'required';
                    }
                    break;

                default:
                    if ($field['type'] === 'file') {
                        $fieldName = "files.{$field['name']}";
                        $rules[$fieldName] = 'nullable|array';
                        $rules[$fieldName . '.*'] = 'file|image|mimes:jpeg,jpg,png,gif,webp|max:10240';
                    } elseif ($field['type'] === 'tel') {
                        $rules[$fieldName] = 'nullable|regex:/^[+]?[0-9\s\-\(\)]{7,20}$/';
                    } else {
                        $rules[$fieldName] = 'nullable';
                    }
                    break;
            }
        }

        if ($this->isTermsAndConditions === true) {
            $rules['formData.termsAndConditions'] = 'required';
        }

        
        
        return $rules;
    }
    public function mount($formTitle, $formIdentifier, $formButtonText, $isTermsAndConditions = false)
    {   
        $this->formTitle = $formTitle;
        $form = FormModel::where('form_identifier', $formIdentifier)->first();
        $this->crmFormId = $form->id;
        $this->formFields = $form->form_fields;
        $this->formButtonText = $formButtonText;
        $this->isTermsAndConditions = $isTermsAndConditions;
        
    }

    public function submit()
    {   
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Scroll to first error when validation fails
            $this->dispatch('scrollToFirstError', formIdentifier: $this->formIdentifier);
            throw $e;
        }

        foreach ($this->files as $fieldName => $file) {
            if ($file) {
                if (is_array($file)) {
                    // Handle multiple files
                    foreach ($file as $singleFile) {
                        // Try to upload to R2 (Cloudflare), fallback to local storage
                        $uuid = Uuid::uuid4()->toString();
                        $filename = $uuid . '_' . time() . '.' . $singleFile->getClientOriginalExtension();
                        
                        try {
                            // Try R2 first
                            if (env('R2_BUCKET')) {
                                $path = $singleFile->storeAs('form-submissions/' . $this->formIdentifier, $filename, ['disk' => 'r2', 'visibility' => 'public']);
                                $baseUrl = Storage::disk('r2')->url('');
                                $url = $baseUrl . 'bags-and-tea/' . $path;
                            } else {
                                throw new \Exception('R2 not configured');
                            }
                        } catch (\Exception $e) {
                            // Fallback to local storage
                            $path = $singleFile->storeAs('form-submissions/' . $this->formIdentifier, $filename, ['disk' => 'public', 'visibility' => 'public']);
                            $url = Storage::disk('public')->url($path);
                        }
                        
                        $this->formData[$fieldName][] = $url;
                    }
                } else {
                    // Handle single file
                    $uuid = Uuid::uuid4()->toString();
                    $filename = $uuid . '_' . time() . '.' . $file->getClientOriginalExtension();
                    
                    try {
                        // Try R2 first
                        if (env('R2_BUCKET')) {
                            $path = $file->storeAs('form-submissions/' . $this->formIdentifier, $filename, ['disk' => 'r2', 'visibility' => 'public']);
                            $baseUrl = Storage::disk('r2')->url('');
                            $url = $baseUrl . 'bags-and-tea/' . $path;
                        } else {
                            throw new \Exception('R2 not configured');
                        }
                    } catch (\Exception $e) {
                        // Fallback to local storage
                        $path = $file->storeAs('form-submissions/' . $this->formIdentifier, $filename, ['disk' => 'public', 'visibility' => 'public']);
                        $url = Storage::disk('public')->url($path);
                    }
                    
                    $this->formData[$fieldName] = $url;
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
        
        $this->showSuccessMessage = true;
        
        // Scroll to form after submission
        $this->dispatch('scrollToForm', formIdentifier: $this->formIdentifier);
    }

    public function render()
    {
        return view('livewire.crm/forms/show');
    }

    

}
