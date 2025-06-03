<?php

namespace App\Auth\User\Domain;

use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository
{
    public function findByEmail(string $email): ?UserEloquentModel;

    public function findById(string $id): ?UserEloquentModel;

    public function findAdmins(): Collection;

    public function findBuyers(): Collection;

    public function findVendors(): Collection;

    public function create(array $data): UserEloquentModel;

    public function update(string $id, array $data): UserEloquentModel;
}