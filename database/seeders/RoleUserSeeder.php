<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Akun untuk kamu (Admin)
    \App\Models\User::create([
        'name' => 'Admin Andikha',
        'email' => 'admin@elvo.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
    ]);

    // Akun untuk ngetes login pelanggan (Rehan)
    \App\Models\User::create([
        'name' => 'Pelanggan Rehan',
        'email' => 'customer@elvo.com',
        'password' => bcrypt('password123'),
        'role' => 'customer',
    ]);
}
}
