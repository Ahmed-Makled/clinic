<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'طب الأسنان',
                'slug' => 'dentistry',
                'description' => 'خدمات طب الأسنان وعلاج الأسنان',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'طب العيون',
                'slug' => 'ophthalmology',
                'description' => 'خدمات طب وجراحة العيون',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'طب الأطفال',
                'slug' => 'pediatrics',
                'description' => 'رعاية صحية للأطفال والرضع',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الطب النفسي',
                'slug' => 'psychiatry',
                'description' => 'علاج الاضطرابات النفسية والعقلية',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
