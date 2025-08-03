<?php

namespace Src\Shared\Frontend;

use Livewire\Component;

class CookieBanner extends Component
{
    public $showBanner = false;
    public $cookiePreferences = [];
    
    public function mount()
    {
        // Show banner by default if user hasn't made a choice
        $this->showBanner = !$this->hasUserMadeChoice();
        
        // Initialize default preferences
        $this->cookiePreferences = [
            'necessary' => true,
            'analytics' => false,
            'marketing' => false,
            'functional' => false,
            'location' => false
        ];
    }
    
    public function acceptAll()
    {
        $preferences = [
            'necessary' => true,
            'analytics' => true,
            'marketing' => true,
            'functional' => true,
            'location' => true,
            'timestamp' => now()->toDateTimeString()
        ];
        
        $this->saveCookiePreferences($preferences);
        $this->showBanner = false;
    }
    
    public function rejectAll()
    {
        $preferences = [
            'necessary' => true,
            'analytics' => false,
            'marketing' => false,
            'functional' => false,
            'location' => false,
            'timestamp' => now()->toDateTimeString()
        ];
        
        $this->saveCookiePreferences($preferences);
        $this->showBanner = false;
    }
    
    public function saveCustomPreferences($preferences)
    {
        $preferences['necessary'] = true; // Always required
        $preferences['timestamp'] = now()->toDateTimeString();
        
        $this->saveCookiePreferences($preferences);
        $this->showBanner = false;
    }
    
    private function hasUserMadeChoice()
    {
        return request()->hasCookie('cookie_preferences');
    }
    
    private function saveCookiePreferences($preferences)
    {
        $cookieValue = base64_encode(json_encode($preferences));
        
        // Set cookie for 1 year
        cookie()->queue('cookie_preferences', $cookieValue, 60 * 24 * 365);
        
        $this->dispatch('cookiePreferencesSaved', $preferences);
    }
    
    public function render()
    {
        return view('livewire.shared.cookie-banner');
    }
}