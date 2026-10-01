<?php

namespace App\Repositories\Eloquent\Authentication;

use App\Models\Authentication\Role;
use App\Repositories\Contracts\Authentication\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(private Role $model) {}

    public function findById(int $id): ?Role
    {
        return $this->model->with('permissions')->find($id);
    }

    public function findByName(string $name): ?Role
    {
        return $this->model->with('permissions')->where('name', $name)->first();
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role->fresh('permissions');
    }

    public function delete(Role $role): bool
    {
        return (bool) $role->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->with('permissions')->get();
    }

    public function syncPermissions(Role $role, array $permissionIds): Role
    {
        $role->permissions()->sync($permissionIds);
        return $role->fresh('permissions');
    }
}