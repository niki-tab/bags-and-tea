<?php

namespace Src\Shared\Frontend;

use Livewire\Component;

class LanguageSelector extends Component
{
    public $currentLanguage;
    public $currentRouteName;

    public function mount()
    {
        $this->currentLanguage = app()->getLocale();
        $this->currentRouteName = request()->route()->getName();
        
        $locale = request()->segment(1);

        if (in_array($locale, ['en'])) {
            app()->setLocale('en');
        } else {
            app()->setLocale('es');
        }
        
    }

    public function switchLanguage()
    {
        $this->currentLanguage = $this->currentLanguage === 'en' ? 'es' : 'en';

        app()->setLocale($this->currentLanguage);

        return redirect()->route($this->currentRouteName, ['locale' => $this->currentLanguage]);
    }

    public function render()
    {
        return view('livewire.shared/language-selector');
    }
}