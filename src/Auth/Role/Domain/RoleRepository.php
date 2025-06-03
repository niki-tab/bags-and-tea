<?php

namespace App\Auth\Role\Domain;

interface RoleRepository
{
    public function findByName(string $name): ?Role;

    public function findById(string $id): ?Role;

    public function findAll(): array;

    public function create(Role $role): Role;

    public function update(Role $role): Role;
}