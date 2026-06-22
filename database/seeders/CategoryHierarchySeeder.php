<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryHierarchySeeder extends Seeder
{
    public function run(): void
    {
        $hierarchy = [
            'Fashion' => [
                'T-Shirt', 'Polo Shirt', 'Jersey', 'Kemeja', 'Long Sleeve',
            ],
            'Outerwear' => [
                'Hoodie', 'Sweater', 'Crewneck', 'Jacket', 'Windbreaker', 'Flannel',
            ],
            'Bawahan' => [
                'Cargo Pants', 'Chino Pants', 'Jogger Pants', 'Jeans', 'Celana Panjang', 'Celana Pendek',
            ],
            'Aksesoris' => [
                'Topi', 'Jam Tangan', 'Kalung', 'Gelang', 'Tas', 'Dompet', 'Kacamata', 'Keychain',
            ],
            'Footwear' => [
                'Sneakers', 'Sandal', 'Boots', 'Slip On',
            ],
            'Elektronik' => [
                'Earphone', 'Headset', 'Smartwatch', 'Powerbank', 'Aksesoris Gadget',
            ],
        ];

        foreach ($hierarchy as $parentName => $children) {
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName, 'slug' => Str::slug($parentName)]
            );

            foreach ($children as $childName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name' => $childName,
                        'slug' => Str::slug($childName),
                        'parent_id' => $parent->id,
                    ]
                );
            }
        }

        $this->command->info('Category hierarchy seeded successfully!');
    }
}
