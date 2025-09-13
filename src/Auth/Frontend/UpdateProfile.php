<?php

namespace Src\Auth\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateProfile extends Component
{
    public $name;
    public $email;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ];
    }

    public function render()
    {
        return view('livewire.auth.update-profile');
    }

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('profile_success', trans('my-account.profile_updated'));
    }
}