<?php

namespace Src\Auth\Frontend;

use Livewire\Component;
use Src\Auth\Application\RegisterUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

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
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    //->symbols()
                    //->uncompromised()
            ],
        ]);

        $user = $registerUser($this->name, $this->email, $this->password);

        Auth::login($user);

        $locale = app()->getLocale();
        $routeName = $locale === 'es' ? 'my-account.show.es' : 'my-account.show.en';
        return redirect()->route($routeName, ['locale' => $locale]);
    }
}
