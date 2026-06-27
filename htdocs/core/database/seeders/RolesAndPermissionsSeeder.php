<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage-users',
            'manage-members',
            'manage-subscriptions',
            'manage-payments',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        $admin = Role::findOrCreate('Admin', 'web');
        $receptionist = Role::findOrCreate('Receptionist', 'web');
        $trainer = Role::findOrCreate('Trainer', 'web');
        $member = Role::findOrCreate('Member', 'web');

        // Ensure Admin always has every permission in the system.
        $admin->syncPermissions(Permission::query()->pluck('name')->all());
        $receptionist->syncPermissions([
            'manage-members',
            'manage-subscriptions',
            'manage-payments',
        ]);
        $trainer->syncPermissions([]);
        $member->syncPermissions([]);
    }
}

