<?php

namespace Src\Products\Product\Frontend;

use Livewire\Component;

class ProductAdditionalInformation extends Component
{
    public $openSection = null;
    public $lang;

    public function mount()
    {
        $this->lang = app()->getLocale();
    }

    public function toggleSection($section)
    {
        // If the same section is clicked, close it; otherwise, open the new section
        $this->openSection = $this->openSection === $section ? null : $section;
    }

    public function render()
    {
        return view('livewire.products.product.additional-information');
    }
}