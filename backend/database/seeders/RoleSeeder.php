<?php

namespace Database\Seeders;

use App\Models\Authentication\Permission;
use App\Models\Authentication\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $platformAdmin = Role::updateOrCreate(
            ['name' => 'platform_admin'],
            ['display_name' => 'Platform Admin', 'is_system' => true],
        );

        $agent = Role::updateOrCreate(
            ['name' => 'agent'],
            ['display_name' => 'Agent / Seller', 'is_system' => true],
        );

        $customer = Role::updateOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Customer', 'is_system' => true],
        );

        // Platform Admin → ALL permissions
        $platformAdmin->permissions()->sync(Permission::pluck('id'));

        // Agent → agency-scoped permissions
        $agentPerms = Permission::whereIn('name', [
            'services.view', 'services.create', 'services.update', 'services.delete',
            'bookings.view', 'bookings.manage',
            'reviews.view',
            'messages.send',
        ])->pluck('id');
        $agent->permissions()->sync($agentPerms);

        // Customer → basic
        $customerPerms = Permission::whereIn('name', [
            'bookings.view',
            'messages.send',
        ])->pluck('id');
        $customer->permissions()->sync($customerPerms);
    }
}