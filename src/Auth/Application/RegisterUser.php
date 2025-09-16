<?php

declare(strict_types=1);

namespace Src\Auth\Application;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class RegisterUser
{
    public function __invoke(string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        SendWelcomeEmailJob::dispatch($user);

        return $user;
    }
}
