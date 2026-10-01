<?php

namespace App\Services\Authentication;

use App\Models\Authentication\Permission;
use App\Repositories\Contracts\Authentication\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PermissionService
{
    public function __construct(private PermissionRepositoryInterface $permissions) {}

    public function all(array $filters = []): Collection
    {
        return $this->permissions->getAll($filters);
    }

    public function find(int $id): Permission
    {
        $p = $this->permissions->findById($id);
        if (!$p) {
            throw ValidationException::withMessages(['id' => ['Permission not found.']]);
        }
        return $p;
    }

    public function create(array $data): Permission
    {
        if ($this->permissions->findByName($data['name'])) {
            throw ValidationException::withMessages(['name' => ['Permission already exists.']]);
        }

        return $this->permissions->create([
            'name'         => $data['name'],
            'guard_name'   => 'web',
            'module'       => $data['module'] ?? null,
            'display_name' => $data['display_name'] ?? $data['name'],
            'description'  => $data['description'] ?? null,
        ]);
    }

    public function update(int $id, array $data): Permission
    {
        $p = $this->find($id);

        return $this->permissions->update($p, [
            'module'       => $data['module']       ?? $p->module,
            'display_name' => $data['display_name'] ?? $p->display_name,
            'description'  => $data['description']  ?? $p->description,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->permissions->delete($this->find($id));
    }
}