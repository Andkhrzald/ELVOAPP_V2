<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAccountsSeeder extends Seeder
{
    /**
     * Update akun admin & tambah akun baru.
     * Jalankan: php artisan db:seed --class=UpdateAccountsSeeder
     */
    public function run(): void
    {
        // Hapus akun lama yang akan diganti
        User::whereIn('email', [
            'admin@elvoapp.com',
            'admin@elvo.com',
            'customer@elvo.com',
        ])->delete();

        // 1. Admin Andikha
        User::create([
            'name'     => 'Admin Andikha',
            'email'    => 'admin1@elvoapp.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '081234567890',
            'address'  => 'Kantor Elvo HQ',
        ]);

        // 2. Admin Rehan
        User::create([
            'name'     => 'Rehan Admin',
            'email'    => 'admin2@elvoapp.com',
            'password' => Hash::make('password2'),
            'role'     => 'admin',
            'phone'    => '081234567891',
            'address'  => 'Kantor Elvo Cabang',
        ]);

        // 3. Owner Admin
        User::create([
            'name'     => 'Admin Owner',
            'email'    => 'owner@elvo.com',
            'password' => Hash::make('password'),
            'role'     => 'owner',
            'phone'    => '081234567892',
            'address'  => 'Rumah Owner Elvo',
        ]);

        // 4. Test Customer
        User::create([
            'name'     => 'Test Customer',
            'email'    => 'testcus@elvo.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
            'phone'    => '081234567893',
            'address'  => 'Jl. Testing No. 1',
        ]);

        $this->command->info('✅ Akun berhasil diperbarui!');
        $this->command->info('   admin1@elvoapp.com / password   → Admin Andikha');
        $this->command->info('   admin2@elvoapp.com / password2  → Rehan Admin');
        $this->command->info('   owner@elvo.com     / password   → Admin Owner');
        $this->command->info('   testcus@elvo.com   / password   → Test Customer');
    }
}
