<?php

namespace Database\Seeders;

use App\Models\Authentication\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@travelplatform.test'],
            [
                'name'              => 'Platform Admin',
                'password'          => Hash::make('password'),
                'user_type'         => 'admin',
                'status'            => 'active',
                'email_verified_at' => now(),
            ]
        );

        $admin->roles()->syncWithoutDetaching([
            \App\Models\Authentication\Role::where('name', 'platform_admin')->value('id')
                => ['model_type' => User::class],
        ]);
    }
}