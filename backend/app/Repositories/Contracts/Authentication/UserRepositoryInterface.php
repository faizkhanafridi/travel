<?php

namespace App\Repositories\Contracts\Authentication;

use App\Models\Authentication\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findBySetupToken(string $token): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}