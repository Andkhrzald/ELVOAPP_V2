# PROGRESS ADMIN DASHBOARD — Elvoapp

> File ini mencatat semua perubahan, aktivitas, dan keputusan terkait pengembangan Admin Dashboard & Analytics Elvoapp.

---

## Ringkasan Data Analytics Saat Ini (per 24 Mei 2026)

| Metrik | Nilai | Keterangan |
|--------|-------|------------|
| **Produk Aktif** | 12 | Fashion apparel (hoodie, t-shirt, celana, aksesoris) |
| **Pelanggan** | 26 | Tersebar daftar 90 hari (Jabodetabek, Bandung, Surabaya, dll) |
| **Orders (revenue)** | 68 | Status selesai/dikirim/proses |
| **Total Revenue** | Rp 57.366.000 | 90 hari terakhir |
| **AOV** | Rp 843.618 | Average Order Value |
| **Rating Rata-rata** | 4.34 / 5.0 | Dari 62 review |
| **Low Stock** | 2 produk | Stok < 5 |

---

## Riwayat Perubahan

### [2026-05-24] — Analytics Demo Data Seeder

**Aksi:** Membuat `AnalyticsDemoSeeder` + eksekusi

**File dibuat:**
- `database/seeders/AnalyticsDemoSeeder.php` — Seeder komprehensif (500+ baris)

**File dimodifikasi:**
- `database/seeders/DatabaseSeeder.php` — Menambah panggilan `AnalyticsDemoSeeder::class`
- Database: memperbarui harga & stok produk existing agar konsisten

**Data yang di-generate:**
- **12 produk** fashion: Hoodie (3), T-Shirt (4), Celana (3), Aksesoris (4)
  - 5 produk existing (diupdate harga/stok) + 7 produk baru
  - Image: file `public/uploads/products/{slug}.jpg` otomatis terdeteksi
- **25 pelanggan** dengan `created_at` tersebar 90 hari:
  - 6 existing (Siti, Andi, Budi, Rehan, Dewi, Test Customer — diperbarui `created_at`)
  - 19 baru (Rina, Fajar, Putri, Dimas, Anita, Hendra, Sari, Aditya, Nining, Rizky, Mita, Dani, Winda, Yoga, Mega, Iqbal, Fitri, Arif, Cantika)
  - Kota: Bandung, Jakarta, Tangsel, Surabaya, Yogyakarta, Bekasi, Medan, Makassar, Bali, Malang, Bogor, Palembang, Depok, Semarang
- **87 order baru** (total 97 dengan existing) tersebar 90 hari:
  - Distribusi: selesai (34), dikirim (18), proses (17), pending (11), minta_batal (3), batal (3), minta_refund (6), refund (5)
  - Masing2 order: 1-3 item, random dari 12 produk
- **62 review** untuk order selesai:
  - Rating 5: 35, Rating 4: 15, Rating 3: 10, Rating 2: 2
  - Komentar realistis Bahasa Indonesia (30+ variasi)
- **272 activity logs** dengan event: order_created, payment_confirmed, order_shipped, order_completed, order_cancelled, refund_processed

**Idempotensi:** Semua data menggunakan `firstOrCreate` — aman di-run berulang kali

**Image produk:** Cek otomatis file di `public/uploads/products/{slug}.jpg` atau `.png`

### [2026-05-24] — Migration Kolom Orders

**Aksi:** Membuat migration untuk kolom `cancel_reason`, `refund_reason`, `previous_status`

**File dibuat:**
- `database/migrations/2026_05_24_080247_add_cancel_refund_columns_to_orders_table.php`

**Catatan:** Kolom ternyata sudah ada di database (kemungkinan ditambahkan manual). Migration di-mark sebagai sudah jalan.

### [2026-05-24] — Pembersihan Data Legacy

**Aksi:** Memperbaiki data lama yang merusak analytics

**Detail:**
- Order `INV-20260507-004` (selesai, Rp 60.205.000) → diubah statusnya jadi `batal`
  - Ini order test dengan produk "Elvo Jennis" (harga Rp 60jt) yang bukan produk fashion
  - Dengan status batal, tidak masuk hitungan revenue analytics

---

## Akun yang Tersedia

| Email | Password | Role | Nama |
|-------|----------|------|------|
| admin1@elvoapp.com | password | admin | Admin Andikha |
| admin2@elvoapp.com | password2 | admin | Rehan Admin |
| owner@elvo.com | password | owner | Amin Owner |
| testcus@elvo.com | password | customer | Test Customer |

Semua pelanggan demo (siti@gmail.com, andi@gmail.com, dll) password: `password`

---

## Cara Menambahkan Foto Produk

Letakkan file gambar di `public/uploads/products/` dengan nama:
- `elvo-signature-hoodie-black.jpg` (atau .png)
- `elvo-basic-t-shirt-white.jpg`
- `elvo-premium-cap.jpg`
- `elvo-cargo-pants-olive.jpg`
- `elvo-oversized-tshirt-navy.jpg`
- `elvo-varsity-jacket.jpg`
- `elvo-tote-bag.jpg`
- `elvo-jogger-pants-black.jpg`
- `elvo-graphic-t-shirt-red.jpg`
- `elvo-beanie.jpg`
- `elvo-bomber-jacket.jpg`
- `elvo-shorts.jpg`

Seeder otomatis mendeteksi file dan mengisi kolom `image` di database.

---

## Catatan Penting

- **PHP 8.3.30** dari Laragon (`C:/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe`) — bukan default system (8.1)
- **Vite** harus `npm run dev` untuk development atau `npm run build` untuk production
- **ApexCharts** di-import via `resources/js/app.js` dan di-assign ke `window.ApexCharts`
- Ada 3 produk legacy yang tidak dihapus (Macbook Pro, ElvoPopKey, Elvo Jennis) — bisa dihapus manual via admin
- Ada 1 produk duplikat (Elvo Basic T-Shirt White slug `elvo-basic-tshirt-white` tanpa T) — bisa dihapus via admin
