<?php

namespace App\Providers;

use App\Auth\User\Domain\UserRepository;
use App\Auth\User\Infrastructure\EloquentUserRepository;
use App\Auth\Role\Domain\RoleRepository;
use App\Auth\Role\Infrastructure\EloquentRoleRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerRepositories();
    }

    /**
     * Register repository bindings.
     */
    private function registerRepositories(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
    }
}
