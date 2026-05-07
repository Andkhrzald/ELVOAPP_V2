<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class AdminDemoSeeder extends Seeder
{
    /**
     * Seeder khusus demo data untuk admin dashboard.
     * Jalankan: php artisan db:seed --class=AdminDemoSeeder
     */
    public function run(): void
    {
        // ============================================
        // 1. AKUN ADMIN
        // ============================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@elvoapp.com'],
            [
                'name' => 'Andikha Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Kantor Elvo HQ',
            ]
        );

        // ============================================
        // 2. AKUN CUSTOMER DEMO
        // ============================================
        $customer1 = User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Kecamatan Sukajadi, Kota Bandung, Jawa Barat 40162',
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'andi@gmail.com'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '085711223344',
                'address' => 'Perumahan Indah Kencana Blok C5, Serpong, Tangerang Selatan, Banten 15310',
            ]
        );

        $customer3 = User::firstOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '089988776655',
                'address' => 'Jl. Gajah Mada No. 45, Kec. Genteng, Surabaya, Jawa Timur 60275',
            ]
        );

        $customer4 = User::firstOrCreate(
            ['email' => 'rehan@gmail.com'],
            [
                'name' => 'Rehan Pratama',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '081299887766',
                'address' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat, DKI Jakarta 11530',
            ]
        );

        $customer5 = User::firstOrCreate(
            ['email' => 'dewi@gmail.com'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '087811223344',
                'address' => 'Jl. Sudirman No. 88, Kota Semarang, Jawa Tengah 50243',
            ]
        );

        // ============================================
        // 3. CATEGORIES (Jika belum ada)
        // ============================================
        $catHoodie = Category::firstOrCreate(
            ['slug' => 'hoodie'],
            ['name' => 'Hoodie']
        );
        $catTshirt = Category::firstOrCreate(
            ['slug' => 't-shirt'],
            ['name' => 'T-Shirt']
        );
        $catAksesoris = Category::firstOrCreate(
            ['slug' => 'aksesoris'],
            ['name' => 'Aksesoris']
        );
        $catCelana = Category::firstOrCreate(
            ['slug' => 'celana'],
            ['name' => 'Celana']
        );

        // ============================================
        // 4. PRODUCTS (Jika belum ada)
        // ============================================
        $prod1 = Product::firstOrCreate(
            ['slug' => 'elvo-signature-hoodie-black'],
            [
                'category_id' => $catHoodie->id,
                'name' => 'Elvo Signature Hoodie Black',
                'description' => 'Hoodie premium Elvo edisi Signature dengan bahan cotton fleece 320gsm. Nyaman, tebal, dan stylish.',
                'price' => 450000,
                'stock' => 25,
                'color' => 'Black',
                'weight' => '500g',
            ]
        );

        $prod2 = Product::firstOrCreate(
            ['slug' => 'elvo-basic-tshirt-white'],
            [
                'category_id' => $catTshirt->id,
                'name' => 'Elvo Basic T-Shirt White',
                'description' => 'Kaos polos Elvo dengan bahan cotton combed 30s. Adem dan cocok untuk daily wear.',
                'price' => 185000,
                'stock' => 50,
                'color' => 'White',
                'weight' => '200g',
            ]
        );

        $prod3 = Product::firstOrCreate(
            ['slug' => 'elvo-premium-cap'],
            [
                'category_id' => $catAksesoris->id,
                'name' => 'Elvo Premium Cap',
                'description' => 'Topi snapback Elvo premium dengan bordir logo eksklusif.',
                'price' => 150000,
                'stock' => 30,
                'color' => 'Black',
                'weight' => '120g',
            ]
        );

        $prod4 = Product::firstOrCreate(
            ['slug' => 'elvo-cargo-pants-olive'],
            [
                'category_id' => $catCelana->id,
                'name' => 'Elvo Cargo Pants Olive',
                'description' => 'Celana cargo Elvo dengan 6 kantong fungsional. Bahan ripstop premium.',
                'price' => 385000,
                'stock' => 15,
                'color' => 'Olive',
                'weight' => '450g',
            ]
        );

        $prod5 = Product::firstOrCreate(
            ['slug' => 'elvo-oversized-tshirt-navy'],
            [
                'category_id' => $catTshirt->id,
                'name' => 'Elvo Oversized T-Shirt Navy',
                'description' => 'Kaos oversized Elvo dengan cutting modern. Cotton combed 24s tebal.',
                'price' => 220000,
                'stock' => 40,
                'color' => 'Navy',
                'weight' => '250g',
            ]
        );

        // ============================================
        // 5. ORDERS + ORDER ITEMS
        // ============================================

        // Order 1 — Siti Aminah (PROSES)
        $order1 = Order::firstOrCreate(
            ['order_number' => 'INV-20260507-001'],
            [
                'user_id' => $customer1->id,
                'total_price' => 1200000,
                'status' => 'proses',
                'payment_method' => 'Transfer Bank BCA',
                'shipping_method' => 'J&T Express - Reguler',
                'shipping_cost' => 50000,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_id' => $prod1->id],
            [
                'product_name' => $prod1->name,
                'quantity' => 2,
                'price' => $prod1->price,
                'subtotal' => 2 * $prod1->price,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_id' => $prod3->id],
            [
                'product_name' => $prod3->name,
                'quantity' => 1,
                'price' => $prod3->price,
                'subtotal' => $prod3->price,
            ]
        );

        // Order 2 — Andi Wijaya (PENDING)
        $order2 = Order::firstOrCreate(
            ['order_number' => 'INV-20260507-002'],
            [
                'user_id' => $customer2->id,
                'total_price' => 350000,
                'status' => 'pending',
                'payment_method' => 'E-Wallet (Gopay)',
                'shipping_method' => 'SiCepat Best',
                'shipping_cost' => 20000,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order2->id, 'product_id' => $prod2->id],
            [
                'product_name' => $prod2->name,
                'quantity' => 1,
                'price' => $prod2->price,
                'subtotal' => $prod2->price,
            ]
        );

        // Order 3 — Budi Santoso (DIKIRIM)
        $order3 = Order::firstOrCreate(
            ['order_number' => 'INV-20260506-003'],
            [
                'user_id' => $customer3->id,
                'total_price' => 500000,
                'status' => 'dikirim',
                'payment_method' => 'Transfer Bank Mandiri',
                'shipping_method' => 'JNE YES',
                'shipping_cost' => 35000,
                'no_resi' => 'JNE-99882233',
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order3->id, 'product_id' => $prod1->id],
            [
                'product_name' => $prod1->name,
                'quantity' => 1,
                'price' => $prod1->price,
                'subtotal' => $prod1->price,
            ]
        );

        // Order 4 — Rehan Pratama (SELESAI)
        $order4 = Order::firstOrCreate(
            ['order_number' => 'INV-20260505-004'],
            [
                'user_id' => $customer4->id,
                'total_price' => 770000,
                'status' => 'selesai',
                'payment_method' => 'Transfer Bank BRI',
                'shipping_method' => 'JNE Reguler',
                'shipping_cost' => 25000,
                'no_resi' => 'JNE-77665544',
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order4->id, 'product_id' => $prod4->id],
            [
                'product_name' => $prod4->name,
                'quantity' => 1,
                'price' => $prod4->price,
                'subtotal' => $prod4->price,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order4->id, 'product_id' => $prod5->id],
            [
                'product_name' => $prod5->name,
                'quantity' => 1,
                'price' => $prod5->price,
                'subtotal' => $prod5->price,
            ]
        );

        // Order 5 — Dewi Lestari (PENDING)
        $order5 = Order::firstOrCreate(
            ['order_number' => 'INV-20260508-005'],
            [
                'user_id' => $customer5->id,
                'total_price' => 635000,
                'status' => 'pending',
                'payment_method' => 'E-Wallet (OVO)',
                'shipping_method' => 'AnterAja Reguler',
                'shipping_cost' => 30000,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order5->id, 'product_id' => $prod1->id],
            [
                'product_name' => $prod1->name,
                'quantity' => 1,
                'price' => $prod1->price,
                'subtotal' => $prod1->price,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order5->id, 'product_id' => $prod2->id],
            [
                'product_name' => $prod2->name,
                'quantity' => 1,
                'price' => $prod2->price,
                'subtotal' => $prod2->price,
            ]
        );

        // ============================================
        // 6. ACTIVITY LOGS
        // ============================================
        ActivityLog::create([
            'user_id' => $customer1->id,
            'action' => 'order_created',
            'description' => 'Pesanan baru #INV-20260507-001 dari Siti Aminah',
            'model_type' => 'Order',
            'model_id' => $order1->id,
            'created_at' => now()->subMinutes(2),
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'stock_updated',
            'description' => 'Stok Elvo Signature Hoodie Black diperbarui (+25 unit)',
            'model_type' => 'Product',
            'model_id' => $prod1->id,
            'created_at' => now()->subHour(),
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'payment_confirmed',
            'description' => 'Pembayaran dikonfirmasi untuk pesanan #INV-20260506-003 (Budi Santoso)',
            'model_type' => 'Order',
            'model_id' => $order3->id,
            'created_at' => now()->subHours(3),
        ]);

        ActivityLog::create([
            'user_id' => $customer2->id,
            'action' => 'order_created',
            'description' => 'Pesanan baru #INV-20260507-002 dari Andi Wijaya',
            'model_type' => 'Order',
            'model_id' => $order2->id,
            'created_at' => now()->subHours(5),
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'order_shipped',
            'description' => 'Pesanan #INV-20260506-003 dikirim via JNE YES (Resi: JNE-99882233)',
            'model_type' => 'Order',
            'model_id' => $order3->id,
            'created_at' => now()->subHours(6),
        ]);

        ActivityLog::create([
            'user_id' => $customer5->id,
            'action' => 'user_registered',
            'description' => 'Pelanggan baru Dewi Lestari mendaftar',
            'model_type' => 'User',
            'model_id' => $customer5->id,
            'created_at' => now()->subHours(8),
        ]);

        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'product_created',
            'description' => 'Produk baru ditambahkan: Elvo Cargo Pants Olive',
            'model_type' => 'Product',
            'model_id' => $prod4->id,
            'created_at' => now()->subDay(),
        ]);

        ActivityLog::create([
            'user_id' => $customer4->id,
            'action' => 'order_completed',
            'description' => 'Pesanan #INV-20260505-004 telah selesai (Rehan Pratama)',
            'model_type' => 'Order',
            'model_id' => $order4->id,
            'created_at' => now()->subDays(2),
        ]);

        $this->command->info('✅ Admin demo data seeded successfully!');
    }
}
