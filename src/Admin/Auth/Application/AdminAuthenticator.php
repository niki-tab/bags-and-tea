<?php

namespace App\Admin\Auth\Application;

use App\Auth\User\Domain\UserRepository;
use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticator
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function authenticate(string $email, string $password): ?UserEloquentModel
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new \InvalidArgumentException('User not found');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \InvalidArgumentException('Invalid password');
        }

        if (!$user->hasRole('admin') && !$user->hasRole('vendor')) {
            throw new \InvalidArgumentException('User is not an admin or vendor');
        }

        Auth::login($user);

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function isAuthenticated(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->hasRole('admin') || $user->hasRole('vendor');
    }
}