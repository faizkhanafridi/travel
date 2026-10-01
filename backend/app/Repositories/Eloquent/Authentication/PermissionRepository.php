<?php

namespace App\Repositories\Eloquent\Authentication;

use App\Models\Authentication\Permission;
use App\Repositories\Contracts\Authentication\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(private Permission $model) {}

    public function findById(int $id): ?Permission
    {
        return $this->model->find($id);
    }

    public function findByName(string $name): ?Permission
    {
        return $this->model->where('name', $name)->first();
    }

    public function create(array $data): Permission
    {
        return $this->model->create($data);
    }

    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission->fresh();
    }

    public function delete(Permission $permission): bool
    {
        return (bool) $permission->delete();
    }

    public function getAll(array $filters = []): Collection
    {
        $q = $this->model->newQuery();
        if (!empty($filters['module'])) {
            $q->where('module', $filters['module']);
        }
        return $q->orderBy('module')->orderBy('name')->get();
    }
}