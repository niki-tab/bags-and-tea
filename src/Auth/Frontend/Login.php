<?php

namespace Src\Auth\Frontend;

use Livewire\Component;
use Src\Auth\Application\LoginUser;

class Login extends Component
{
    public $email;
    public $password;

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login(LoginUser $loginUser)
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($loginUser($this->email, $this->password)) {
            $locale = app()->getLocale();
            $routeName = $locale === 'es' ? 'my-account.show.es' : 'my-account.show.en';
            return redirect()->route($routeName, ['locale' => $locale]);
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
}
