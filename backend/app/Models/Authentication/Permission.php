<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name', 'guard_name', 'module', 'display_name', 'description',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
}