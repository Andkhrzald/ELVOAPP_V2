<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AnalyticsDemoSeeder extends Seeder
{
    private array $productDefs = [
        ['slug' => 'elvo-signature-hoodie-black', 'name' => 'Elvo Signature Hoodie Black', 'cat' => 'hoodie',
            'price' => 450000, 'stock' => 50, 'color' => 'Black', 'weight' => '500g',
            'desc' => 'Hoodie premium Elvo edisi Signature dengan bahan cotton fleece 320gsm. Nyaman, tebal, dan stylish.'],
        ['slug' => 'elvo-basic-t-shirt-white', 'name' => 'Elvo Basic T-Shirt White', 'cat' => 't-shirt',
            'price' => 185000, 'stock' => 100, 'color' => 'White', 'weight' => '200g',
            'desc' => 'Kaos polos Elvo dengan bahan cotton combed 30s. Adem dan cocok untuk daily wear.'],
        ['slug' => 'elvo-premium-cap', 'name' => 'Elvo Premium Cap', 'cat' => 'aksesoris',
            'price' => 150000, 'stock' => 60, 'color' => 'Black', 'weight' => '120g',
            'desc' => 'Topi snapback Elvo premium dengan bordir logo eksklusif.'],
        ['slug' => 'elvo-cargo-pants-olive', 'name' => 'Elvo Cargo Pants Olive', 'cat' => 'celana',
            'price' => 385000, 'stock' => 30, 'color' => 'Olive', 'weight' => '450g',
            'desc' => 'Celana cargo Elvo dengan 6 kantong fungsional. Bahan ripstop premium.'],
        ['slug' => 'elvo-oversized-t-shirt-navy', 'name' => 'Elvo Oversized T-Shirt Navy', 'cat' => 't-shirt',
            'price' => 220000, 'stock' => 80, 'color' => 'Navy', 'weight' => '250g',
            'desc' => 'Kaos oversized Elvo dengan cutting modern. Cotton combed 24s tebal.'],
        ['slug' => 'elvo-varsity-jacket', 'name' => 'Elvo Varsity Jacket', 'cat' => 'hoodie',
            'price' => 650000, 'stock' => 20, 'color' => 'Navy/White', 'weight' => '700g',
            'desc' => 'Varsity jacket Elvo dengan bahan wool blend premium. Kombinasi warna navy & putih.'],
        ['slug' => 'elvo-tote-bag', 'name' => 'Elvo Tote Bag', 'cat' => 'aksesoris',
            'price' => 95000, 'stock' => 100, 'color' => 'Natural', 'weight' => '200g',
            'desc' => 'Tote bag kanvas Elvo dengan sablon logo premium. Cocok untuk daily use.'],
        ['slug' => 'elvo-jogger-pants-black', 'name' => 'Elvo Jogger Pants Black', 'cat' => 'celana',
            'price' => 285000, 'stock' => 40, 'color' => 'Black', 'weight' => '350g',
            'desc' => 'Jogger pants Elvo bahan fleece premium. Nyaman dipakai santai maupun olahraga.'],
        ['slug' => 'elvo-graphic-t-shirt-red', 'name' => 'Elvo Graphic T-Shirt Red', 'cat' => 't-shirt',
            'price' => 199000, 'stock' => 60, 'color' => 'Red', 'weight' => '220g',
            'desc' => 'Kaos graphic Elvo dengan desain eksklusif. Bahan cotton combed 30s premium.'],
        ['slug' => 'elvo-beanie', 'name' => 'Elvo Beanie', 'cat' => 'aksesoris',
            'price' => 85000, 'stock' => 70, 'color' => 'Black', 'weight' => '80g',
            'desc' => 'Beanie Elvo rajutan premium. Hangat dan nyaman dipakai sehari-hari.'],
        ['slug' => 'elvo-bomber-jacket', 'name' => 'Elvo Bomber Jacket', 'cat' => 'hoodie',
            'price' => 550000, 'stock' => 15, 'color' => 'Army Green', 'weight' => '600g',
            'desc' => 'Bomber jacket Elvo dengan bahan drill premium. Lapisan dalam windbreaker.'],
        ['slug' => 'elvo-shorts', 'name' => 'Elvo Shorts', 'cat' => 'celana',
            'price' => 165000, 'stock' => 50, 'color' => 'Black', 'weight' => '200g',
            'desc' => 'Shorts Elvo bahan cotton fleece. Nyaman dipakai santai maupun olahraga.'],
    ];

    private array $customerDefs = [
        ['name' => 'Siti Aminah', 'email' => 'siti@gmail.com', 'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 123, Kec. Sukajadi, Kota Bandung, Jawa Barat 40162', 'daysAgo' => 90],
        ['name' => 'Andi Wijaya', 'email' => 'andi@gmail.com', 'phone' => '085711223344',
            'address' => 'Perumahan Indah Kencana Blok C5, Serpong, Tangerang Selatan, Banten 15310', 'daysAgo' => 87],
        ['name' => 'Budi Santoso', 'email' => 'budi@gmail.com', 'phone' => '089988776655',
            'address' => 'Jl. Gajah Mada No. 45, Kec. Genteng, Surabaya, Jawa Timur 60275', 'daysAgo' => 83],
        ['name' => 'Rehan Pratama', 'email' => 'rehan@gmail.com', 'phone' => '081299887766',
            'address' => 'Jl. Kebon Jeruk No. 10, Jakarta Barat, DKI Jakarta 11530', 'daysAgo' => 79],
        ['name' => 'Dewi Lestari', 'email' => 'dewi@gmail.com', 'phone' => '087811223344',
            'address' => 'Jl. Sudirman No. 88, Kota Semarang, Jawa Tengah 50243', 'daysAgo' => 75],
        ['name' => 'Test Customer', 'email' => 'testcus@elvo.com', 'phone' => '081234567893',
            'address' => 'Jl. Testing No. 1, Jakarta Pusat', 'daysAgo' => 2],
        ['name' => 'Rina Marlina', 'email' => 'rina@gmail.com', 'phone' => '082112345678',
            'address' => 'Jl. Riau No. 56, Kec. Coblong, Kota Bandung, Jawa Barat 40132', 'daysAgo' => 74],
        ['name' => 'Fajar Hidayat', 'email' => 'fajar@gmail.com', 'phone' => '081298765432',
            'address' => 'Jl. Senopati No. 21, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12190', 'daysAgo' => 70],
        ['name' => 'Putri Wulandari', 'email' => 'putri@gmail.com', 'phone' => '085612345678',
            'address' => 'Jl. Malioboro No. 45, Kec. Gedongtengen, Kota Yogyakarta, DIY 55271', 'daysAgo' => 67],
        ['name' => 'Dimas Pratama', 'email' => 'dimas@gmail.com', 'phone' => '081387654321',
            'address' => 'Perumahan Pondok Gede Permai Blok A12, Bekasi, Jawa Barat 17141', 'daysAgo' => 63],
        ['name' => 'Anita Rahmawati', 'email' => 'anita@gmail.com', 'phone' => '085265432198',
            'address' => 'Jl. Sisingamangaraja No. 78, Kec. Medan Kota, Kota Medan, Sumut 20212', 'daysAgo' => 59],
        ['name' => 'Hendra Gunawan', 'email' => 'hendra@gmail.com', 'phone' => '082187654312',
            'address' => 'Jl. Pettarani No. 34, Kec. Panakkukang, Kota Makassar, Sulsel 90231', 'daysAgo' => 55],
        ['name' => 'Sari Indah', 'email' => 'sari@gmail.com', 'phone' => '081239874561',
            'address' => 'Jl. Raya Kuta No. 100, Kec. Kuta, Kab. Badung, Bali 80361', 'daysAgo' => 51],
        ['name' => 'Aditya Saputra', 'email' => 'aditya@gmail.com', 'phone' => '085723456789',
            'address' => 'Jl. Dago No. 156, Kec. Coblong, Kota Bandung, Jawa Barat 40135', 'daysAgo' => 47],
        ['name' => 'Nining Susanti', 'email' => 'nining@gmail.com', 'phone' => '081334567890',
            'address' => 'Jl. Ijen No. 23, Kec. Klojen, Kota Malang, Jawa Timur 65115', 'daysAgo' => 43],
        ['name' => 'Rizky Kurniawan', 'email' => 'rizky@gmail.com', 'phone' => '085898765432',
            'address' => 'Jl. Pramuka No. 12, Kec. Matraman, Jakarta Timur, DKI Jakarta 13120', 'daysAgo' => 39],
        ['name' => 'Mita Anggraini', 'email' => 'mita@gmail.com', 'phone' => '082134567891',
            'address' => 'Jl. Siliwangi No. 67, Kec. Bogor Tengah, Kota Bogor, Jawa Barat 16124', 'daysAgo' => 35],
        ['name' => 'Dani Permana', 'email' => 'dani@gmail.com', 'phone' => '081287654323',
            'address' => 'Perumahan Alam Sutera Blok F8, Kec. Serpong, Kota Tangerang Selatan, Banten 15325', 'daysAgo' => 31],
        ['name' => 'Winda Agustina', 'email' => 'winda@gmail.com', 'phone' => '085676543210',
            'address' => 'Jl. Jend. Sudirman No. 234, Kec. Ilir Timur I, Kota Palembang, Sumsel 30127', 'daysAgo' => 27],
        ['name' => 'Yoga Pratama', 'email' => 'yoga@gmail.com', 'phone' => '081345678912',
            'address' => 'Jl. Setiabudi No. 89, Kec. Sukasari, Kota Bandung, Jawa Barat 40154', 'daysAgo' => 23],
        ['name' => 'Mega Syahputri', 'email' => 'mega@gmail.com', 'phone' => '082198765434',
            'address' => 'Jl. Thamrin No. 45, Kec. Gambir, Jakarta Pusat, DKI Jakarta 10110', 'daysAgo' => 19],
        ['name' => 'Iqbal Maulana', 'email' => 'iqbal@gmail.com', 'phone' => '085534567891',
            'address' => 'Jl. Kayoon No. 12, Kec. Genteng, Kota Surabaya, Jawa Timur 60275', 'daysAgo' => 14],
        ['name' => 'Fitri Handayani', 'email' => 'fitri@gmail.com', 'phone' => '081256789012',
            'address' => 'Perumahan Cinere Indah Blok D5, Kec. Limo, Kota Depok, Jawa Barat 16514', 'daysAgo' => 9],
        ['name' => 'Arif Setiawan', 'email' => 'arif@gmail.com', 'phone' => '085712345890',
            'address' => 'Jl. Pandanaran No. 34, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50131', 'daysAgo' => 5],
        ['name' => 'Cantika Putri', 'email' => 'cantika@gmail.com', 'phone' => '082245678901',
            'address' => 'Jl. Pluit Raya No. 78, Kec. Penjaringan, Jakarta Utara, DKI Jakarta 14450', 'daysAgo' => 1],
    ];

    private array $paymentMethods = [
        'Transfer Bank BCA', 'E-Wallet (Gopay)', 'Transfer Bank Mandiri',
        'E-Wallet (OVO)', 'Transfer Bank BRI', 'COD (Bayar di Tempat)',
    ];

    private array $shippingMethods = [
        ['method' => 'J&T Express - Reguler', 'cost' => 25000],
        ['method' => 'J&T Express - Reguler', 'cost' => 30000],
        ['method' => 'J&T Express - Reguler', 'cost' => 20000],
        ['method' => 'SiCepat Best', 'cost' => 22000],
        ['method' => 'SiCepat Best', 'cost' => 28000],
        ['method' => 'JNE YES', 'cost' => 45000],
        ['method' => 'JNE YES', 'cost' => 50000],
        ['method' => 'JNE Reguler', 'cost' => 25000],
        ['method' => 'JNE Reguler', 'cost' => 30000],
        ['method' => 'AnterAja Reguler', 'cost' => 18000],
        ['method' => 'AnterAja Reguler', 'cost' => 22000],
    ];

    private array $statuses = [
        ['status' => 'selesai', 'weight' => 38],
        ['status' => 'dikirim', 'weight' => 20],
        ['status' => 'proses', 'weight' => 15],
        ['status' => 'pending', 'weight' => 12],
        ['status' => 'batal', 'weight' => 5],
        ['status' => 'refund', 'weight' => 4],
        ['status' => 'minta_batal', 'weight' => 3],
        ['status' => 'minta_refund', 'weight' => 3],
    ];

    private array $reviewComments = [
        5 => [
            'Barangnya keren banget! Kualitas premium, sesuai ekspektasi. Recommended!',
            'Bahannya adem, jahitan rapi, pengiriman cepat. Pasti repeat order!',
            'Sumpah puas banget, packing aman, barang original. Mantap pokoknya!',
            'Quality 10/10! Warnanya sesuai foto, size-nya pas. Makasih Elvo!',
            'Udah beli beberapa kali, selalu konsisten kualitasnya. Love it!',
            'Pengiriman super cepat, barang sesuai deskripsi. Bakal jadi langganan nih!',
            'Keren abis! Bahannya tebal dan nyaman dipakai. Recommended banget!',
            'Pelayanan ramah, respon cepat, barang sampai dengan baik. Top markotop!',
            'Puas banget! Detail jahitannya rapi, bahan halus. Gak nyesel beli.',
            'Suka banget sama produk Elvo. Worth it banget buat kualitas segini.',
        ],
        4 => [
            'Bagus overall, cuma ukurannya agak kebesaran. Tapi bahannya adem.',
            'Mantap, sesuai foto. Cuma pengirimannya agak lama. Tapi worth it.',
            'Produk bagus, cuma sayang kemasan luarnya agak penyok. Barangnya aman.',
            'Bahan nyaman, warna sesuai. Mungkin next order size-nya turun satu.',
            'Boleh lah, kualitas standar brand lokal. Puas cukup.',
            'Enak dipakai, bahannya adem. Jahitan ada sedikit benang lepas, tapi overall ok.',
            'Design keren dan simple. Cocok buat daily outfit. Recommend!',
            'Sesuai ekspektasi, cuma pengiriman butuh waktu agak lama. Barang oke.',
        ],
        3 => [
            'Standar aja sih, biasa aja. Tapi gak jelek juga.',
            'Lumayan lah buat harganya, sesuai kantong.',
            'Bi biasa aja, bahannya tipis. Mungkin next coba varian lain.',
            'Masih ok sih, cuma ekspektasi gue lebih. Tapi not bad.',
            'Sesuai harga sih, gak lebih gak kurang. Cukup.',
            'Pas dipakai agak longgar, tapi masih bisa dipakai. Overall lumayan.',
        ],
        2 => [
            'Kurang puas, ukuran gak sesuai sama yang dipesen.',
            'Warna agak beda dari foto, lebih pudar. Agak kecewa.',
            'Jahitannya ada yang kurang rapi. Masih layak pakai sih.',
            'Kualitas ok, tapi salah kirim ukuran. Terpaksa dipakai.',
        ],
        1 => [
            'Kecewa berat, barang datang rusak padahal packing aman. Minta return.',
            'Gak sesuai ekspektasi sama sekali. Kualitas jelek, gak worth it.',
        ],
    ];

    private array $activityActions = [
        'order_created', 'payment_confirmed', 'order_shipped',
        'order_completed', 'order_cancelled', 'refund_processed',
    ];

    private array $products = [];

    public function run(): void
    {
        $this->command->info(' Seeding analytics demo data...');

        $categories = $this->createCategories();
        $this->products = $this->createProducts($categories);
        $customers = $this->createCustomers();
        $this->generateOrders($customers);

        $this->command->info('✅ Analytics demo data seeded successfully!');
        $this->printSummary();
    }

    private function createCategories(): array
    {
        $catDefs = [
            ['slug' => 'hoodie', 'name' => 'Hoodie'],
            ['slug' => 't-shirt', 'name' => 'T-Shirt'],
            ['slug' => 'aksesoris', 'name' => 'Aksesoris'],
            ['slug' => 'celana', 'name' => 'Celana'],
        ];
        $categories = [];
        foreach ($catDefs as $def) {
            $cat = Category::firstOrCreate(['slug' => $def['slug']], ['name' => $def['name']]);
            $categories[$def['slug']] = $cat;
        }
        return $categories;
    }

    private function createProducts(array $categories): array
    {
        $products = [];
        foreach ($this->productDefs as $def) {
            $createData = [
                'category_id' => $categories[$def['cat']]->id,
                'name' => $def['name'],
                'description' => $def['desc'],
                'price' => $def['price'],
                'stock' => $def['stock'],
                'color' => $def['color'],
                'weight' => $def['weight'],
                'is_active' => true,
            ];
            $product = Product::firstOrCreate(['slug' => $def['slug']], $createData);
            if (!$product->wasRecentlyCreated) {
                $product->update([
                    'price' => $def['price'],
                    'stock' => $def['stock'],
                    'is_active' => true,
                ]);
            }
            if (!$product->image) {
                $img = $this->resolveImage($def['slug']);
                if ($img) {
                    $product->update(['image' => $img]);
                }
            }
            $products[] = $product;
        }
        return $products;
    }

    private function resolveImage(string $slug): ?string
    {
        $jpg = 'products/' . $slug . '.jpg';
        if (file_exists(public_path('uploads/' . $jpg))) {
            return $jpg;
        }
        $png = 'products/' . $slug . '.png';
        if (file_exists(public_path('uploads/' . $png))) {
            return $png;
        }
        return null;
    }

    private function createCustomers(): array
    {
        $customers = [];
        foreach ($this->customerDefs as $def) {
            $createdAt = Carbon::now()->subDays($def['daysAgo']);
            $data = [
                'name' => $def['name'],
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => $def['phone'],
                'address' => $def['address'],
            ];
            $customer = User::firstOrCreate(['email' => $def['email']], $data);
            $customer->update([
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            $customers[] = $customer;
        }
        return $customers;
    }

    private function generateOrders(array $customers): void
    {
        $startDate = Carbon::now()->subDays(89)->startOfDay();
        $totalOrders = 0;
        $totalReviews = 0;
        $totalLogs = 0;
        $usedOrderNumbers = [];

        foreach (Order::pluck('order_number')->toArray() as $on) {
            $usedOrderNumbers[$on] = true;
        }

        $this->command->info(' Generating orders over 90 days...');
        $progress = $this->command->getOutput()->createProgressBar(90);

        for ($day = 0; $day < 90; $day++) {
            $date = (clone $startDate)->addDays($day);

            if ($day < 20) $maxOrders = 0;
            elseif ($day < 40) $maxOrders = 1;
            elseif ($day < 60) $maxOrders = 2;
            elseif ($day < 80) $maxOrders = 3;
            else $maxOrders = 4;

            $dayOrders = random_int(0, $maxOrders);

            for ($i = 0; $i < $dayOrders; $i++) {
                $orderDate = (clone $date)->addHours(random_int(7, 22))->addMinutes(random_int(0, 59));
                $customer = $customers[array_rand($customers)];

                $numItems = random_int(1, 3);
                $chosenProducts = (array)array_rand(array_flip(range(0, count($this->products) - 1)), $numItems);
                if (!is_array($chosenProducts)) $chosenProducts = [$chosenProducts];

                $subtotal = 0;
                $orderItems = [];
                foreach ($chosenProducts as $pi) {
                    $product = $this->products[$pi];
                    $qty = random_int(1, 2);
                    $itemSubtotal = $product->price * $qty;
                    $subtotal += $itemSubtotal;
                    $orderItems[] = [$product, $qty, $itemSubtotal];
                }

                $shipping = $this->shippingMethods[array_rand($this->shippingMethods)];
                $totalPrice = $subtotal + $shipping['cost'];

                $status = $this->weightedRandomStatus();

                $pmWeights = [4, 4, 2, 2, 1.5, 0.5];
                $pmIndex = $this->weightedRandomIndex($pmWeights);
                $paymentMethod = $this->paymentMethods[$pmIndex];

                $noResi = null;
                $cancelReason = null;
                $refundReason = null;
                $previousStatus = null;

                if (in_array($status, ['dikirim', 'selesai'])) {
                    $couriers = ['JNE', 'J&T', 'SICEPAT', 'SICEPAT', 'JNE', 'J&T'];
                    $noResi = $couriers[array_rand($couriers)] . '-' . str_pad(random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);
                }

                if ($status === 'minta_batal') {
                    $reasons = [
                        'Saya ingin mengganti alamat pengiriman.',
                        'Saya salah pilih ukuran, mau order ulang.',
                        'Mohon dibatalkan, ada perubahan rencana.',
                        'Ganda order, mohon salah satunya dibatalkan.',
                    ];
                    $cancelReason = $reasons[array_rand($reasons)];
                    $previousStatus = 'pending';
                }

                if ($status === 'batal') {
                    $reasons = [
                        'Tidak ada respon dari pembeli.',
                        'Pembeli meminta pembatalan.',
                        'Stok habis, tidak bisa diproses.',
                    ];
                    $cancelReason = $reasons[array_rand($reasons)];
                    $previousStatus = 'proses';
                }

                if ($status === 'minta_refund') {
                    $reasons = [
                        'Barang yang sampai ukurannya tidak sesuai.',
                        'Barang cacat/rusak saat diterima.',
                        'Warna tidak sesuai dengan yang dipesan.',
                    ];
                    $refundReason = $reasons[array_rand($reasons)];
                    $previousStatus = 'dikirim';
                }

                if ($status === 'refund') {
                    $reasons = [
                        'Barang cacat produksi, refund disetujui.',
                        'Ukuran tidak sesuai, refund diproses.',
                        'Barang rusak dalam pengiriman, refund diberikan.',
                    ];
                    $refundReason = $reasons[array_rand($reasons)];
                    $previousStatus = 'dikirim';
                }

                $orderNumber = $this->generateOrderNumber($orderDate, $usedOrderNumbers);

                $order = Order::firstOrCreate(
                    ['order_number' => $orderNumber],
                    [
                        'user_id' => $customer->id,
                        'total_price' => $totalPrice,
                        'status' => $status,
                        'payment_method' => $paymentMethod,
                        'shipping_method' => $shipping['method'],
                        'shipping_cost' => $shipping['cost'],
                        'no_resi' => $noResi,
                        'cancel_reason' => $cancelReason,
                        'refund_reason' => $refundReason,
                        'previous_status' => $previousStatus,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]
                );

                if (!$order->wasRecentlyCreated) continue;

                foreach ($orderItems as [$product, $qty, $itemSubtotal]) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $qty,
                        'price' => $product->price,
                        'subtotal' => $itemSubtotal,
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }
                $totalOrders++;

                if ($status === 'selesai') {
                    $this->createReviews($order, $orderItems, $orderDate, $customer);
                    $totalReviews += count($orderItems);
                }

                $this->createActivityLogs($order, $customer, $orderDate, $status);
                $totalLogs++;
            }
            $progress->advance();
        }

        $progress->finish();
        $this->command->newLine();
        $this->command->info(" Done: {$totalOrders} orders, {$totalReviews} reviews, {$totalLogs} activity logs.");
    }

    private function generateOrderNumber(Carbon $date, array &$used): string
    {
        $prefix = $date->format('Ymd');
        for ($attempt = 0; $attempt < 100; $attempt++) {
            $seq = str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);
            $number = "INV-{$prefix}-{$seq}";
            if (!isset($used[$number])) {
                $used[$number] = true;
                return $number;
            }
        }
        $seq = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        return "INV-{$prefix}-{$seq}";
    }

    private function createReviews(Order $order, array $orderItems, Carbon $orderDate, User $customer): void
    {
        $reviewDate = (clone $orderDate)->addDays(random_int(1, 5))->addHours(random_int(8, 21));

        foreach ($orderItems as [$product, $qty, $itemSubtotal]) {
            $ratingWeights = [2 => [0, 0, 1, 2, 5]];
            $rating = $this->weightedRandomRating();
            $comments = $this->reviewComments[$rating];
            $comment = $comments[array_rand($comments)];

            Review::firstOrCreate(
                ['user_id' => $customer->id, 'product_id' => $product->id, 'order_id' => $order->id],
                [
                    'rating' => $rating,
                    'comment' => $comment,
                    'created_at' => $reviewDate,
                    'updated_at' => $reviewDate,
                ]
            );
        }
    }

    private function createActivityLogs(Order $order, User $customer, Carbon $orderDate, string $status): void
    {
        if (Order::where('order_number', $order->order_number)->exists()) {
            $existing = ActivityLog::where('model_type', 'Order')
                ->where('model_id', $order->id)
                ->where('action', 'order_created')
                ->exists();
            if ($existing) return;
        }

        $logs = [
            [
                'action' => 'order_created',
                'description' => "Pesanan baru #{$order->order_number} dari {$customer->name}",
                'time' => clone $orderDate,
            ],
        ];

        if (in_array($status, ['proses', 'dikirim', 'selesai'])) {
            $logs[] = [
                'action' => 'payment_confirmed',
                'description' => "Pembayaran dikonfirmasi untuk pesanan #{$order->order_number}",
                'time' => (clone $orderDate)->addHours(random_int(1, 6)),
            ];
        }

        if (in_array($status, ['dikirim', 'selesai'])) {
            $logs[] = [
                'action' => 'order_shipped',
                'description' => "Pesanan #{$order->order_number} dikirim via {$order->shipping_method} (Resi: {$order->no_resi})",
                'time' => (clone $orderDate)->addDays(random_int(1, 3))->addHours(random_int(8, 17)),
            ];
        }

        if ($status === 'selesai') {
            $logs[] = [
                'action' => 'order_completed',
                'description' => "Pesanan #{$order->order_number} telah selesai ({$customer->name})",
                'time' => (clone $orderDate)->addDays(random_int(3, 7))->addHours(random_int(8, 21)),
            ];
        }

        if ($status === 'batal') {
            $logs[] = [
                'action' => 'order_cancelled',
                'description' => "Pesanan #{$order->order_number} dibatalkan. Alasan: {$order->cancel_reason}",
                'time' => (clone $orderDate)->addHours(random_int(2, 24)),
            ];
        }

        if ($status === 'refund') {
            $logs[] = [
                'action' => 'refund_processed',
                'description' => "Refund diproses untuk pesanan #{$order->order_number}. Alasan: {$order->refund_reason}",
                'time' => (clone $orderDate)->addDays(random_int(3, 7))->addHours(random_int(8, 17)),
            ];
        }

        foreach ($logs as $log) {
            ActivityLog::create([
                'user_id' => $customer->id,
                'action' => $log['action'],
                'description' => $log['description'],
                'model_type' => 'Order',
                'model_id' => $order->id,
                'created_at' => $log['time'],
                'updated_at' => $log['time'],
            ]);
        }
    }

    private function weightedRandomStatus(): string
    {
        $weights = array_column($this->statuses, 'weight');
        $index = $this->weightedRandomIndex($weights);
        return $this->statuses[$index]['status'];
    }

    private function weightedRandomRating(): int
    {
        $weights = [1, 3, 10, 25, 50];
        $total = array_sum($weights);
        $rand = random_int(1, $total);
        $cumulative = 0;
        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) return $rating + 1;
        }
        return 5;
    }

    private function weightedRandomIndex(array $weights): int
    {
        $total = array_sum($weights);
        $rand = random_int(1, (int)($total * 100)) / 100;
        $cumulative = 0;
        foreach ($weights as $i => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) return $i;
        }
        return count($weights) - 1;
    }

    private function printSummary(): void
    {
        $orderCount = Order::count();
        $itemCount = OrderItem::count();
        $reviewCount = Review::count();
        $logCount = ActivityLog::count();
        $productCount = Product::count();
        $customerCount = User::where('role', 'customer')->count();

        $this->command->info('');
        $this->command->info(' Analytics Data Summary:');
        $this->command->info("   Products : {$productCount}");
        $this->command->info("   Customers: {$customerCount}");
        $this->command->info("   Orders   : {$orderCount}");
        $this->command->info("   Items    : {$itemCount}");
        $this->command->info("   Reviews  : {$reviewCount}");
        $this->command->info("   Logs     : {$logCount}");
    }
}
