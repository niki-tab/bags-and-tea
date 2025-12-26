<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class TrustpilotFooter extends Component
{
    public $ratingScore = 4.6;
    public $maxStars = 5;

    public function render()
    {
        return view('livewire.shared.trustpilot-footer');
    }
}
