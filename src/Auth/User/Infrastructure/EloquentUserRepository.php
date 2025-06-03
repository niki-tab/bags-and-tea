<?php

namespace App\Auth\User\Infrastructure;

use App\Auth\User\Domain\UserRepository;
use App\Auth\User\Infrastructure\Eloquent\UserEloquentModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepository
{
    public function __construct(
        private UserEloquentModel $model
    ) {}

    public function findByEmail(string $email): ?UserEloquentModel
    {
        return $this->model->where('email', $email)->first();
    }

    public function findById(string $id): ?UserEloquentModel
    {
        return $this->model->find($id);
    }

    public function findAdmins(): Collection
    {
        return $this->model->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
    }

    public function findBuyers(): Collection
    {
        return $this->model->whereHas('roles', function ($query) {
            $query->where('name', 'buyer');
        })->get();
    }

    public function findVendors(): Collection
    {
        return $this->model->whereHas('roles', function ($query) {
            $query->where('name', 'vendor');
        })->get();
    }

    public function create(array $data): UserEloquentModel
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): UserEloquentModel
    {
        $eloquentUser = $this->model->find($id);
        
        if (!$eloquentUser) {
            throw new \InvalidArgumentException("User with ID {$id} not found");
        }

        $eloquentUser->update($data);

        return $eloquentUser->fresh();
    }
}