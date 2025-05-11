<?php

namespace Database\Seeders;

use Modules\Users\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@clinic.com'],
            [
                'name' => 'Admin',
                'phone_number' => '01066181943',
                'password' => Hash::make('password'),
                'status' => true
            ]
        );
        $admin->assignRole('Admin');

        $secondAdmin = User::firstOrCreate(
            ['email' => 'ahmed.makled@live.com'],
            [
                'name' => 'Ahmed Makled',
                'phone_number' => '01066181942',
                'password' => Hash::make('01066181942'),
                'status' => true
            ]
        );
        $secondAdmin->assignRole('Admin');
    }
}
