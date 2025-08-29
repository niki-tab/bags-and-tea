<?php

namespace Src\Auth\Frontend;

use Livewire\Component;
use Src\Auth\Application\RegisterUser;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function register(RegisterUser $registerUser)
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = $registerUser($this->name, $this->email, $this->password);

        Auth::login($user);

        return redirect()->to('/my-account');
    }
}
