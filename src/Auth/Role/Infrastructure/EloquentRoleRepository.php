<?php

namespace App\Auth\Role\Infrastructure;

use App\Auth\Role\Domain\Role;
use App\Auth\Role\Domain\RoleRepository;
use App\Auth\Role\Infrastructure\Eloquent\RoleEloquentModel;

class EloquentRoleRepository implements RoleRepository
{
    public function __construct(
        private RoleEloquentModel $model
    ) {}

    public function findByName(string $name): ?Role
    {
        $eloquentRole = $this->model->where('name', $name)->first();
        
        return $eloquentRole ? $this->toDomain($eloquentRole) : null;
    }

    public function findById(string $id): ?Role
    {
        $eloquentRole = $this->model->find($id);
        
        return $eloquentRole ? $this->toDomain($eloquentRole) : null;
    }

    public function findAll(): array
    {
        $eloquentRoles = $this->model->all();
        
        return $eloquentRoles->map(fn($role) => $this->toDomain($role))->toArray();
    }

    public function create(Role $role): Role
    {
        $eloquentRole = $this->model->create([
            'id' => $role->getId(),
            'name' => $role->getName(),
            'display_name' => $role->getDisplayName(),
        ]);

        return $this->toDomain($eloquentRole);
    }

    public function update(Role $role): Role
    {
        $eloquentRole = $this->model->find($role->getId());
        
        if (!$eloquentRole) {
            throw new \InvalidArgumentException("Role with ID {$role->getId()} not found");
        }

        $eloquentRole->update([
            'name' => $role->getName(),
            'display_name' => $role->getDisplayName(),
        ]);

        return $this->toDomain($eloquentRole->fresh());
    }

    private function toDomain(RoleEloquentModel $eloquentRole): Role
    {
        return new Role(
            id: $eloquentRole->id,
            name: $eloquentRole->name,
            displayName: $eloquentRole->display_name
        );
    }
}