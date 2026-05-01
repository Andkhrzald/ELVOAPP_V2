<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Buat Kategori dulu
    $kategori = \App\Models\Category::create([
        'name' => 'Hoodie',
        'slug' => 'hoodie',
    ]);

    // Buat Produk yang nyambung ke kategori di atas
    \App\Models\Product::create([
        'category_id' => $kategori->id,
        'name' => 'Elvo Hoodie Signature',
        'slug' => 'elvo-hoodie-signature',
        'description' => 'Hoodie kualitas premium dari Elvoapp',
        'price' => 150000,
        'stock' => 20,
        'image' => 'hoodie-1.jpg',
    ]);
}
}
