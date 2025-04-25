<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Delete existing permissions and roles to prevent duplicates
        Permission::query()->delete();
        Role::query()->delete();

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage roles',
            'manage permissions',
            'manage doctors',
            'manage patients',
            'view doctors',
            'manage appointments',
            'view appointments',

        ];

        // Create all permissions with web guard
        $createdPermissions = collect();
        foreach ($permissions as $permission) {
            $createdPermissions->push(
                Permission::create(['name' => $permission, 'guard_name' => 'web'])
            );
        }

        // Create roles with web guard and assign created permissions
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions($createdPermissions);

        $doctor = Role::create(['name' => 'Doctor', 'guard_name' => 'web']);
        $doctor->syncPermissions($createdPermissions->filter(function ($permission) {
            return in_array($permission->name, [
                'view users',
                'manage appointments',
                'view appointments'
            ]);
        }));

        $patient = Role::create(['name' => 'Patient', 'guard_name' => 'web']);
        $patient->syncPermissions($createdPermissions->filter(function ($permission) {
            return in_array($permission->name, [
                'view doctors',
                'view appointments',
                'manage appointments'
            ]);
        }));
    }
}
