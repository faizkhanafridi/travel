<?php

namespace App\Repositories\Contracts\Authentication;

use App\Models\Authentication\Permission;
use Illuminate\Database\Eloquent\Collection;

interface PermissionRepositoryInterface
{
    public function findById(int $id): ?Permission;

    public function findByName(string $name): ?Permission;

    public function create(array $data): Permission;

    public function update(Permission $permission, array $data): Permission;

    public function delete(Permission $permission): bool;

    public function getAll(array $filters = []): Collection;
}