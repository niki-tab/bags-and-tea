<?php

namespace Src\Crm\Forms\Frontend;

use Livewire\Component;
use App\Models\ProductModel;
use App\Models\ProductSizeVariationModel;
use Src\Blog\Articles\Model\ArticleModel;
use App\Models\ProductQuantityVariationModel;
use App\Models\ProducSizeVariationQuantityVariationPriceModel;

class Form extends Component
{

    public $formTitle;
    
    public $formFields;

    public function mount($formTitle, $formFields)
    {   
        $this->formTitle = $formTitle;
        $this->formFields = $formFields;
        
    }



    public function render()
    {
        return view('livewire.crm/forms/show');
    }

    

}
