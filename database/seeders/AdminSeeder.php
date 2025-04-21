<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('admin:assign', [
            'user_id' => 1
        ]);
    }
}
