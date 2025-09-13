<?php

declare(strict_types=1);

namespace Src\Auth\Application;

use Illuminate\Support\Facades\Auth;

final class LoginUser
{
    public function __invoke(string $email, string $password): bool
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed...
            return true;
        }

        return false;
    }
}
