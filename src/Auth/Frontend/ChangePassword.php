<?php

namespace Src\Auth\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
        'new_password_confirmation' => 'required',
    ];

    public function render()
    {
        return view('livewire.auth.change-password');
    }

    public function changePassword()
    {
        $this->validate();

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('password_error', trans('my-account.current_password_incorrect'));
            return;
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Clear form
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_success', trans('my-account.password_updated'));
    }
}