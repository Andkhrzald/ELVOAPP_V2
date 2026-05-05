<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'category_id' => Category::all()->random()->id ?? 1,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(50000, 500000),
            'stock' => fake()->numberBetween(1, 50),
            'color' => fake()->safeColorName(),
            'weight' => fake()->numberBetween(100, 1000) . 'gr',
            'image' => 'default.jpg',
        ];
    }
}