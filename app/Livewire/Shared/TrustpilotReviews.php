<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class TrustpilotReviews extends Component
{
    public $ratingText;
    public $ratingScore = 4.4;
    public $reviewCount = 9;
    public $maxStars = 5;

    public function mount()
    {
        $this->ratingText = __('shared.excellent');
    }

    public function render()
    {
        return view('livewire.shared.trustpilot-reviews');
    }
}
