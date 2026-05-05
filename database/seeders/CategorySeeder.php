<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::insert([
            ['id' => 1, 'name' => 'Hoodie', 'slug' => 'hoodie'],
            ['id' => 2, 'name' => 'Aksesoris', 'slug' => 'aksesoris'],
            ['id' => 3, 'name' => 'T-Shirt', 'slug' => 't-shirt'],
        ]);
    }
}
