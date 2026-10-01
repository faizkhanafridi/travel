<?php

namespace App\Services\Authentication;

use App\Models\Authentication\Role;
use App\Repositories\Contracts\Authentication\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class RoleService
{
    public function __construct(private RoleRepositoryInterface $roles) {}

    public function all(): Collection
    {
        return $this->roles->getAll();
    }

    public function find(int $id): Role
    {
        $role = $this->roles->findById($id);
        if (!$role) {
            throw ValidationException::withMessages(['id' => ['Role not found.']]);
        }
        return $role;
    }

    public function create(array $data): Role
    {
        if ($this->roles->findByName($data['name'])) {
            throw ValidationException::withMessages(['name' => ['Role already exists.']]);
        }

        $role = $this->roles->create([
            'name'         => $data['name'],
            'guard_name'   => 'web',
            'display_name' => $data['display_name'] ?? $data['name'],
            'description'  => $data['description'] ?? null,
        ]);

        if (!empty($data['permissions'])) {
            $role = $this->roles->syncPermissions($role, $data['permissions']);
        }

        return $role;
    }

    public function update(int $id, array $data): Role
    {
        $role = $this->find($id);

        if ($role->is_system) {
            throw ValidationException::withMessages(['id' => ['System roles cannot be modified.']]);
        }

        $role = $this->roles->update($role, [
            'display_name' => $data['display_name'] ?? $role->display_name,
            'description'  => $data['description']  ?? $role->description,
        ]);

        if (isset($data['permissions'])) {
            $role = $this->roles->syncPermissions($role, $data['permissions']);
        }

        return $role;
    }

    public function delete(int $id): bool
    {
        $role = $this->find($id);

        if ($role->is_system) {
            throw ValidationException::withMessages(['id' => ['System roles cannot be deleted.']]);
        }

        return $this->roles->delete($role);
    }

    public function syncPermissions(int $roleId, array $permissionIds): Role
    {
        $role = $this->find($roleId);
        return $this->roles->syncPermissions($role, $permissionIds);
    }
}