<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\FakeDataSeeder;

class SeedFakeDataCommand extends Command
{
    protected $signature = 'db:seed:fake';

    protected $description = 'Seed the database with fake data for development and testing';

    public function handle(): int
    {
        $this->info('Seeding fake data into the database...');

        $this->call('db:seed', [
            '--class' => FakeDataSeeder::class,
        ]);

        $this->info('Fake data has been seeded successfully!');

        return Command::SUCCESS;
    }
}
