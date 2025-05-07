<?php

namespace Src\Shared\Frontend;

use Livewire\Component;

class WhatsappWidget extends Component
{
    public function __construct()
    {
    }

    public function render()
    {
        return view('livewire.shared/whatsapp-widget');
    }
}