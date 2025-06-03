<?php

namespace App\Livewire\Admin\Auth;

use App\Admin\Auth\Application\AdminAuthenticator;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ];

    protected $messages = [
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 6 characters.',
    ];

    public function authenticate()
    {
        $this->validate();

        $authenticated = app(AdminAuthenticator::class)->authenticate(
            $this->email,
            $this->password
        );

        if (!$authenticated) {
            $this->addError('email', 'Invalid credentials or insufficient permissions.');
            return;
        }

        session()->flash('success', 'Welcome to the admin panel!');
        return redirect()->route('admin.home');
    }

    public function render()
    {
        return view('livewire.admin.auth.login');
    }
}