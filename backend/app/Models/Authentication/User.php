<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'user_type', 'status',
        'phone', 'avatar', 'password_setup_token', 'password_setup_expires_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'password_setup_token',
    ];

    protected $casts = [
        'email_verified_at'          => 'datetime',
        'password_setup_expires_at'  => 'datetime',
        'password'                   => 'hashed',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'model_has_roles',
            'model_id',
            'role_id'
        )->wherePivot('model_type', self::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('name', $permission))
            ->exists();
    }

    public function assignRole(string $role): void
    {
        $r = Role::where('name', $role)->firstOrFail();

        $this->roles()->attach($r->id, ['model_type' => self::class]);
    }

    public function isAdmin(): bool    { return $this->user_type === 'admin'; }
    public function isAgent(): bool    { return $this->user_type === 'agent'; }
    public function isCustomer(): bool { return $this->user_type === 'customer'; }
}