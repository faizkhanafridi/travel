<?php

namespace Database\Seeders;

use App\Models\Authentication\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Auth module
            ['name' => 'auth.view',   'module' => 'auth'],
            ['name' => 'auth.manage', 'module' => 'auth'],

            // Agents
            ['name' => 'agents.view',    'module' => 'agents'],
            ['name' => 'agents.create',  'module' => 'agents'],
            ['name' => 'agents.update',  'module' => 'agents'],
            ['name' => 'agents.delete',  'module' => 'agents'],
            ['name' => 'agents.verify',  'module' => 'agents'],

            // Services
            ['name' => 'services.view',    'module' => 'services'],
            ['name' => 'services.create',  'module' => 'services'],
            ['name' => 'services.update',  'module' => 'services'],
            ['name' => 'services.delete',  'module' => 'services'],
            ['name' => 'services.feature', 'module' => 'services'],

            // Bookings
            ['name' => 'bookings.view',   'module' => 'bookings'],
            ['name' => 'bookings.manage', 'module' => 'bookings'],

            // Payments
            ['name' => 'payments.view',   'module' => 'payments'],
            ['name' => 'payments.manage', 'module' => 'payments'],
            ['name' => 'payouts.manage',  'module' => 'payments'],

            // Reviews
            ['name' => 'reviews.view',   'module' => 'reviews'],
            ['name' => 'reviews.manage', 'module' => 'reviews'],

            // Messaging
            ['name' => 'messages.send', 'module' => 'messaging'],

            // Admin / System
            ['name' => 'settings.manage', 'module' => 'system'],
            ['name' => 'reports.view',    'module' => 'system'],
            ['name' => 'logs.view',       'module' => 'system'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(
                ['name' => $p['name']],
                [
                    'guard_name'   => 'web',
                    'module'       => $p['module'],
                    'display_name' => $p['name'],
                ]
            );
        }
    }
}