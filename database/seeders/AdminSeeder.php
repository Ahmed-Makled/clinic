<?php

namespace Database\Seeders;

use Modules\User\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create first admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@clinic.com',
            'phone_number' => '01066181943',
            'password' => Hash::make('password'),
        ]);
        $adminRole = Role::findByName('Administrator', 'web');
        $admin->assignRole($adminRole);

        // Create second admin user
        $secondAdmin = User::create([
            'name' => 'Ahmed Makled',
            'email' => 'ahmed.makled@live.com',
            'phone_number' => '01066181942',
            'password' => Hash::make('01066181942'),
        ]);
        $secondAdmin->assignRole($adminRole);
    }
}
