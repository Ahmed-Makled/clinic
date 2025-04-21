<?php

namespace Database\Seeders;

use Modules\User\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@clinic.com',
            'phone_number' => '1234567890',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('Administrator');
    }
}
