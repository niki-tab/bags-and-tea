<?php

namespace Src\Shared\Frontend;

use Livewire\Component;

class LanguageSelector extends Component
{
    public $currentLanguage;
    public $currentRouteName;
    public $routeSpanish;
    public $routeEnglish;
    public $paramsSpanish;
    public $paramsEnglish;

    public function mount()
    {
        $this->currentLanguage = app()->getLocale();
        $this->currentRouteName = request()->route()->getName();
        
        $locale = request()->segment(1);

        $isMultiLanguage = str_ends_with($this->currentRouteName, '.en-es');

        if ($isMultiLanguage) {

            $this->routeSpanish = $this->currentRouteName;
            $this->routeEnglish = $this->currentRouteName;

        } else {

            $routeParts = explode('.', $this->currentRouteName);

            // Remove the last element
            array_pop($routeParts);
    
            $this->routeSpanish  = $routeParts;
            $this->routeSpanish[] = "es";
            $this->routeSpanish = implode('.', $this->routeSpanish);
            
            $this->routeEnglish  = $routeParts;
            $this->routeEnglish[] = "en";
            $this->routeEnglish = implode('.', $this->routeEnglish);

        }

        if($this->currentRouteName == "article.show.es" || $this->currentRouteName == "article.show.en"){
            $this->paramsSpanish = ["locale" => "es", 'articleSlug' => request()->route('articleSlug')];
            $this->paramsEnglish = ["locale" => "en", 'articleSlug' => request()->route('articleSlug')];
        }else{
            $this->paramsSpanish = ["locale" => "es"];
            $this->paramsEnglish = ["locale" => "en"];
        }

        
    }

    public function switchLanguage()
    {
        $this->currentLanguage = $this->currentLanguage === 'en' ? 'es' : 'en';

        //return redirect()->route($newRouteName, ['locale' => $this->currentLanguage]);
    }

    public function render()
    {
        return view('livewire.shared/language-selector');
    }
}