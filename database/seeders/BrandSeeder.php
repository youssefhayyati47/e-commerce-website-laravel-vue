<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'Apple',
            'slug' => 'apple',
        ]);

        Brand::create([
            'name' => 'Samsung',
            'slug' => 'samsung',
        ]);

        Brand::create([
            'name' => 'Xiaomi',
            'slug' => 'xiaomi',
        ]);

        Brand::create([
            'name' => 'Huawei',
            'slug' => 'huawei',
        ]);
    }
}
