<?php

namespace App\Admin\Auth\Application;

use App\Auth\User\Domain\UserRepository;
use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;

class AdminAuthenticator
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function authenticate(string $email, string $password): ?UserEloquentModel
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            $this->logFailedLogin($email, 'User not found');
            throw new \InvalidArgumentException('User not found');
        }

        if (!Hash::check($password, $user->password)) {
            $this->logFailedLogin($email, 'Invalid password');
            throw new \InvalidArgumentException('Invalid password');
        }

        if (!$user->hasRole('admin') && !$user->hasRole('vendor')) {
            $this->logFailedLogin($email, 'User is not an admin or vendor');
            throw new \InvalidArgumentException('User is not an admin or vendor');
        }

        Auth::login($user);

        activity('admin-auth')
            ->causedBy($user)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'email' => $email,
            ])
            ->log('Admin login successful');

        return $user;
    }

    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            activity('admin-auth')
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Admin logout');
        }

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

    private function logFailedLogin(string $email, string $reason): void
    {
        activity('admin-auth')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'email' => $email,
                'reason' => $reason,
            ])
            ->log('Admin login failed');
    }
}