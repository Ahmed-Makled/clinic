<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);

        $this->call([
            CategorySeeder::class,
            GovernorateAndCitySeeder::class,
        ]);

        if ($this->command->option('fake')) {
            $this->call(FakeDataSeeder::class);
            $this->command->info('Fake data has been added successfully!');
        }

        // Add test data seeder
        if ($this->command->confirm('Do you want to seed test data ?', false)) {
            $this->call(LargeScaleDataSeeder::class);
        }
    }
}
