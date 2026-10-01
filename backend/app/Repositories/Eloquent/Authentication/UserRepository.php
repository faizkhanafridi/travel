<?php

namespace App\Repositories\Eloquent\Authentication;

use App\Models\Authentication\User;
use App\Repositories\Contracts\Authentication\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $model) {}

    public function findById(int $id): ?User
    {
        return $this->model->with('roles.permissions')->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->with('roles.permissions')->where('email', $email)->first();
    }

    public function findBySetupToken(string $token): ?User
    {
        return $this->model
            ->where('password_setup_token', $token)
            ->where('password_setup_expires_at', '>', now())
            ->first();
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh('roles.permissions');
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('roles.permissions');

        if (!empty($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"));
        }

        return $query->latest()->paginate($perPage);
    }
}