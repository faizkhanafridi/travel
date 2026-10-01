<?php

namespace App\Providers;

use App\Repositories\Contracts\Authentication\PermissionRepositoryInterface;
use App\Repositories\Contracts\Authentication\RoleRepositoryInterface;
use App\Repositories\Contracts\Authentication\UserRepositoryInterface;
use App\Repositories\Eloquent\Authentication\PermissionRepository;
use App\Repositories\Eloquent\Authentication\RoleRepository;
use App\Repositories\Eloquent\Authentication\UserRepository;
use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class,       UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class,       RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    public function boot(): void {}
}