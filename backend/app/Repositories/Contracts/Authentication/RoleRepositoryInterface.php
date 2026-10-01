<?php

namespace App\Repositories\Contracts\Authentication;

use App\Models\Authentication\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    public function findById(int $id): ?Role;

    public function findByName(string $name): ?Role;

    public function create(array $data): Role;

    public function update(Role $role, array $data): Role;

    public function delete(Role $role): bool;

    public function getAll(): Collection;

    public function syncPermissions(Role $role, array $permissionIds): Role;
}