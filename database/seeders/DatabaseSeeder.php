<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles first
        $this->call(RoleSeeder::class);

        // Then create the admin user and assign role
        $this->call(AdminSeeder::class);

        // Other seeders
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
