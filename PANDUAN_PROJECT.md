# 📘 PANDUAN PROJECT ELVOAPP V2

> **Nama Project:** ELVOAPP V2  
> **Repo GitHub:** https://github.com/Andkhrzald/ELVOAPP_V2.git  
> **Framework:** Laravel 13 + Vite + TailwindCSS v4 + Flowbite  
> **Database:** MySQL  
> **Branch Aktif:** `main`, `andikha`, `Rehan`

---

## 📌 DAFTAR ISI

1. [Penjelasan Project](#1--penjelasan-project)
2. [Tech Stack & Struktur Folder](#2--tech-stack--struktur-folder)
3. [Cara Setup & Running Project (Pertama Kali)](#3--cara-setup--running-project-pertama-kali)
4. [Cara Running Sehari-hari](#4--cara-running-sehari-hari)
5. [Panduan Git Kolaborasi Tim](#5--panduan-git-kolaborasi-tim)
6. [Cara Agar Gambar Produk Muncul di Semua Laptop](#6--cara-agar-gambar-produk-muncul-di-semua-laptop)
7. [Cara Sync Database Antar Anggota Tim](#7--cara-sync-database-antar-anggota-tim)
8. [SOP: Alur Lengkap Push Fitur Baru](#8--sop-alur-lengkap-push-fitur-baru)
9. [Troubleshooting / Masalah Umum](#9--troubleshooting--masalah-umum)
10. [Akun Demo](#10--akun-demo)

---

## 1. 📖 Penjelasan Project

**ELVOAPP V2** adalah platform **E-Commerce** berbasis web untuk brand fashion **ELVO**. 

### Fitur Utama:

| Fitur | Keterangan |
|-------|-----------|
| 🏠 Homepage | Landing page brand dengan showcase produk |
| 🛍️ Shop / Toko | Catalog produk dengan filter kategori |
| 🛒 Cart & Checkout | Keranjang belanja + proses checkout |
| 📦 Pesanan Saya | Tracking status pesanan (pending → proses → dikirim → selesai) |
| 📜 Riwayat Transaksi | Histori transaksi yang sudah selesai/batal/refund |
| ⭐ Review Produk | Customer bisa kasih rating & review setelah pesanan selesai |
| 🔐 Auth | Login & Register (Customer & Admin) |
| **ADMIN DASHBOARD** | |
| 📊 Dashboard | Statistik penjualan, chart, activity log |
| 📦 Kelola Produk | CRUD produk (tambah, edit, hapus, upload gambar) |
| 📋 Pesanan Masuk | Manajemen pesanan (terima, kirim, selesaikan, batal, refund) |
| 💰 Transaksi | Riwayat semua transaksi |
| 👥 Pelanggan | Data pelanggan + detail pesanan per customer |
| ⭐ Review | Moderasi review dari customer |
| **OWNER PANEL** | |
| 👑 Owner Dashboard | Dashboard khusus owner dengan overview sistem |
| 👥 Manajemen Admin | CRUD akun admin & owner (hanya owner) |
| 📜 Audit Log | Semua aktivitas sistem (lengkap dengan pagination) |

### Struktur Route:

- **Customer:** `/`, `/shop`, `/checkout`, `/history`, `/riwayat`, `/login`, `/register`
- **Admin:** `/admin/dashboard`, `/admin/products`, `/admin/pesanan-masuk`, `/admin/transaksi`, `/admin/pelanggan`, `/admin/reviews`

---

## 2. 🛠️ Tech Stack & Struktur Folder

### Tech Stack:
| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.3 + Laravel 13 |
| Frontend | Blade Template + TailwindCSS v4 + Flowbite |
| Build Tool | Vite 8 |
| Database | MySQL |
| Package Manager | Composer (PHP) + NPM (Node.js) |
| Chart | ApexCharts |
| Version Control | Git + GitHub |

### Struktur Folder Penting:
```
elvoapp/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          ← Controller admin (Dashboard, Product, Order, dll)
│   │   └── Customer/       ← Controller customer (ShopController)
│   └── Models/             ← Model (User, Product, Order, Category, dll)
│
├── database/
│   ├── migrations/         ← File migrasi database (PENTING: jangan diedit sembarangan!)
│   └── seeders/            ← Data dummy (AdminDemoSeeder, CategorySeeder, dll)
│
├── resources/
│   └── views/
│       ├── admin/          ← Halaman admin (dashboard, products, pesanan, dll)
│       ├── customer/       ← Halaman customer (home, shop, checkout, dll)
│       └── layouts/        ← Layout template (app.blade.php, customer.blade.php)
│
├── routes/
│   ├── web.php             ← Route customer
│   └── admin.php           ← Route admin
│
├── public/
│   ├── img/                ← Gambar statis (logo, background)
│   └── uploads/
│       └── products/       ← ⭐ GAMBAR PRODUK DISIMPAN DI SINI
│
├── .env                    ← Konfigurasi lokal (DB, APP_KEY) — JANGAN DI-PUSH!
├── .env.example            ← Template konfigurasi (yang di-push ke Git)
├── composer.json           ← Dependency PHP
├── package.json            ← Dependency Node.js
└── vite.config.js          ← Konfigurasi Vite build tool
```

---

## 3. 🚀 Cara Setup & Running Project (Pertama Kali)

### Prasyarat yang Harus Di-install:
| Software | Download |
|----------|----------|
| PHP 8.3+ | https://windows.php.net/download/ atau via XAMPP/Laragon |
| Composer | https://getcomposer.org/download/ |
| Node.js 18+ | https://nodejs.org/ |
| MySQL | Bisa pakai XAMPP, Laragon, atau MySQL standalone |
| Git | https://git-scm.com/download/win |

> 💡 **Rekomendasi:** Pakai **Laragon** karena sudah include PHP, MySQL, Composer, dan otomatis setting PATH.

### Step-by-Step Setup:

#### Step 1: Clone Repository
```bash
git clone https://github.com/Andkhrzald/ELVOAPP_V2.git
cd ELVOAPP_V2
```

#### Step 2: Install Dependency PHP (Composer)
```bash
composer install
```

#### Step 3: Install Dependency Node.js (NPM)
```bash
npm install
```

#### Step 4: Buat File `.env`
```bash
copy .env.example .env
```

#### Step 5: Generate APP_KEY
```bash
php artisan key:generate
```

#### Step 6: Setup Database MySQL

1. Buka **phpMyAdmin** atau MySQL client
2. Buat database baru dengan nama: **`elvoapp`**
3. Edit file `.env`, pastikan konfigurasi database seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elvoapp
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ Jika pakai password MySQL, isi `DB_PASSWORD` sesuai password kamu.

#### Step 7: Jalankan Migrasi Database
```bash
php artisan migrate
```

#### Step 8: Jalankan Seeder (Data Demo)
```bash
php artisan db:seed --class=AdminDemoSeeder
```

#### Step 9: Buat Symbolic Link untuk Storage (Opsional)
```bash
php artisan storage:link
```

#### Step 10: Jalankan Server! 🎉

Buka **2 terminal** secara bersamaan:

**Terminal 1 — PHP Server (Backend):**
```bash
php artisan serve
```
> Server jalan di: **http://127.0.0.1:8000**

**Terminal 2 — Vite Dev Server (Frontend/CSS/JS):**
```bash
npm run dev
```
> Vite jalan di: **http://localhost:5173** (ini otomatis, tidak perlu dibuka manual)

#### ATAU: Jalankan Semua Sekaligus (1 Terminal)
```bash
composer run dev
```
> Perintah ini otomatis jalankan `php artisan serve` + `npm run dev` + `queue:listen` + `pail` sekaligus!

#### Step 11: Buka di Browser
```
http://127.0.0.1:8000
```

---

## 4. 🔄 Cara Running Sehari-hari

Setiap kali mau ngoding, cukup:

```bash
# Buka terminal di folder project
cd C:\elvoapp

# Cara cepat (1 terminal untuk semua):
composer run dev

# ATAU cara manual (2 terminal):
# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev
```

> 📌 **PENTING:** `npm run dev` WAJIB dijalankan agar TailwindCSS dan Vite aktif. Kalau tidak, tampilan web akan rusak/kosong.

---

## 5. 🤝 Panduan Git Kolaborasi Tim

### Konsep Dasar

```
main (branch utama, stabil)
  ├── andikha (branch Andikha)
  └── Rehan (branch Rehan)
```

- **`main`** = Branch utama yang STABIL. Jangan langsung ngoding di sini.
- **`andikha`** = Branch kerja Andikha
- **`Rehan`** = Branch kerja Rehan

### ⚡ ATURAN EMAS:
1. ❌ **JANGAN** langsung push ke `main`
2. ✅ **SELALU** kerja di branch masing-masing
3. ✅ **SELALU** pull dulu sebelum mulai ngoding
4. ✅ **SELALU** merge dari `main` ke branch kamu sebelum push

---

### 5.1 — Alur Kerja Andikha (Branch: `andikha`)

#### Sebelum Mulai Ngoding (WAJIB!):
```bash
# 1. Pindah ke branch main dulu
git checkout main

# 2. Ambil update terbaru dari GitHub
git pull origin main

# 3. Pindah ke branch kamu
git checkout andikha

# 4. Merge update main ke branch kamu
git merge main

# 5. Jika ada migration baru dari Rehan, jalankan:
php artisan migrate

# 6. Mulai ngoding! 🚀
```

#### Setelah Selesai Ngoding:
```bash
# 1. Cek file apa saja yang berubah
git status

# 2. Tambahkan semua perubahan
git add .

# 3. Commit dengan pesan yang jelas
git commit -m "feat: tambah fitur keranjang belanja"

# 4. Push ke GitHub
git push origin andikha
```

#### Gabungkan ke Main (Setelah Yakin Fitur Stabil):
```bash
# 1. Pindah ke main
git checkout main

# 2. Pull terbaru
git pull origin main

# 3. Merge branch kamu ke main
git merge andikha

# 4. Push ke main
git push origin main

# 5. Kembali ke branch kamu
git checkout andikha
```

### 5.2 — Alur Kerja Rehan (Branch: `Rehan`)

Sama persis seperti di atas, ganti `andikha` dengan `Rehan`:

```bash
# Sebelum ngoding:
git checkout main
git pull origin main
git checkout Rehan
git merge main
php artisan migrate

# Setelah ngoding:
git add .
git commit -m "feat: tambah halaman profil user"
git push origin Rehan

# Merge ke main:
git checkout main
git pull origin main
git merge Rehan
git push origin main
git checkout Rehan
```

---

## 6. 🖼️ Cara Agar Gambar Produk Muncul di Semua Laptop

### Workflow Upload Gambar (PENTING!):
Kamu **TIDAK PERLU** memindahkan gambar secara manual dari folder `Downloads` ke `public/img`.

1.  **Admin Upload**: Buka dashboard admin, pilih file dari folder mana saja (misal `Downloads`).
2.  **Sistem Bekerja**: Saat kamu klik "Simpan", Laravel akan otomatis menyalin file tersebut ke dalam project kamu di folder: `public/uploads/products/`.
3.  **File Sekarang di Project**: Cek folder `public/uploads/products/` di VS Code, gambar baru pasti sudah ada di sana.
4.  **Share ke Teman**: Lakukan `git add`, `git commit`, dan `git push` agar file tersebut terkirim ke GitHub.

### ✅ Langkah Sinkronisasi untuk Andikha (yang upload gambar):

```bash
# 1. Setelah tambah/edit produk di web admin:
git add public/uploads/products/
git commit -m "chore: tambah gambar produk [Nama Produk]"
git push origin andikha
```

### ✅ Langkah Sinkronisasi untuk Rehan (agar gambar muncul):

```bash
# 1. Tarik update terbaru dari GitHub
git checkout main
git pull origin main
git checkout Rehan
git merge main

# 2. Gambar otomatis ter-download ke folder public/uploads/products/ Anda! ✅
```

#### ⚠️ Pastikan `.gitignore` TIDAK mengabaikan folder uploads

Cek file `.gitignore` di root project. Pastikan **TIDAK ADA** baris berikut:
```
/public/uploads
```

Jika ada, **hapus baris tersebut** agar gambar bisa di-commit.

> 📌 Saat ini `.gitignore` project kalian sudah aman — folder `public/uploads/` TIDAK di-ignore, jadi gambar bisa masuk Git.

### Gambar Statis (Logo, Background):
Gambar statis seperti logo disimpan di `public/img/` dan sudah otomatis masuk Git.

---

## 7. 🗄️ Cara Sync Data Produk (Database)

### Masalah:
Meskipun gambarnya sudah ada (setelah `git pull`), data produk (Nama, Harga, dll) di database lokal Rehan **belum ada**.

### Solusi 1: Update Seeder (Sangat Disarankan)
Jika kamu ingin produk baru tersebut ada di semua laptop selamanya:

1.  Buka file `database/seeders/AdminDemoSeeder.php`.
2.  Tambahkan data produk baru kamu di dalam kode tersebut.
3.  Push file `AdminDemoSeeder.php` ke Git.
4.  Teman kamu tinggal menjalankan: `php artisan db:seed --class=AdminDemoSeeder`.

### Solusi 2: Export SQL (Jika banyak perubahan)
1.  Andikha export tabel `products` via phpMyAdmin ke file `.sql`.
2.  Kirim file `.sql` ke Rehan via WhatsApp/Discord.
3.  Rehan import file tersebut di phpMyAdmin lokalnya.

> 📌 **SOP TERBAIK:** Selalu gunakan **Migrations** untuk struktur tabel dan **Seeders** untuk data awal agar tim tetap sinkron secara otomatis.

---

## 8. 🗄️ Cara Sync Database Antar Anggota Tim

### Prinsip Utama:
> **Database TIDAK di-share lewat Git.** Setiap orang punya database lokal masing-masing. Yang di-share adalah **file migration** dan **seeder**.

### 7.1 — Jika Ada Tabel Baru / Kolom Baru

**Yang Buat (misal Andikha):**
```bash
# 1. Buat file migration
php artisan make:migration create_nama_tabel_table

# 2. Edit file migration di database/migrations/
# 3. Jalankan migration di laptop sendiri
php artisan migrate

# 4. Commit & Push
git add database/migrations/
git commit -m "feat: tambah tabel wishlist"
git push origin andikha
```

**Yang Menerima (misal Rehan):**
```bash
# 1. Pull update terbaru (ikuti langkah di bagian 5)
git checkout main
git pull origin main
git checkout Rehan
git merge main

# 2. Jalankan migration agar database lokal ikut update
php artisan migrate

# ✅ Database Rehan sekarang sama strukturnya dengan Andikha!
```

### 7.2 — Jika Ada Data Demo Baru

**Yang Buat:**
```bash
# 1. Edit file seeder (misal AdminDemoSeeder.php)
# 2. Jalankan seeder
php artisan db:seed --class=AdminDemoSeeder

# 3. Commit & Push
git add database/seeders/
git commit -m "feat: tambah data demo produk baru"
git push origin andikha
```

**Yang Menerima:**
```bash
# 1. Pull update
# 2. Jalankan seeder
php artisan db:seed --class=AdminDemoSeeder
```

### 7.3 — Jika Ada Perubahan Akun / Role Baru

Ada kalanya kita update akun admin, role, atau data user. Caranya:

**Yang Buat:**
```bash
# 1. Buat file seeder baru (misal UpdateAccountsSeeder.php)
# 2. Jalankan seeder untuk update akun
php artisan db:seed --class=UpdateAccountsSeeder

# 3. Commit & Push
git add database/seeders/UpdateAccountsSeeder.php
git commit -m "chore: update akun admin + tambah role owner"
git push origin [branch-kamu]
```

**Yang Menerima:**
```bash
# 1. Pull update
git checkout main
git pull origin main
git merge [branch-kamu]

# 2. Jalankan seeder
php artisan db:seed --class=UpdateAccountsSeeder

# ✅ Akun baru sudah tersedia di lokal kamu!
```

### 7.4 — Jika Database Bermasalah / Mau Reset Total

```bash
# ⚠️ HATI-HATI: Ini akan HAPUS semua data dan buat ulang dari awal!

# 1. Fresh migration (hapus semua tabel + buat ulang)
php artisan migrate:fresh

# 2. Jalankan semua seeder secara berurutan
php artisan db:seed --class=AdminDemoSeeder
php artisan db:seed --class=UpdateAccountsSeeder
```

---

## 8. 📋 SOP: Alur Lengkap Push Fitur Baru

Ini adalah **step-by-step lengkap** setiap kali kamu mau menambah fitur baru:

### FASE 1: Persiapan (Sebelum Ngoding)

```bash
# 1. Buka terminal, masuk folder project
cd C:\elvoapp

# 2. Simpan pekerjaan yang belum di-commit (jika ada)
git stash

# 3. Pindah ke main & pull terbaru
git checkout main
git pull origin main

# 4. Pindah ke branch kamu
git checkout andikha    # atau: git checkout Rehan

# 5. Merge update dari main
git merge main

# 6. Kembalikan pekerjaan yang di-stash (jika tadi ada)
git stash pop

# 7. Jalankan migration (kalau ada migration baru dari teman)
php artisan migrate

# 8. Jalankan server
composer run dev
```

### FASE 2: Ngoding Fitur

Kerjakan fitur kamu. Misalnya:
- Buat controller baru
- Buat migration baru  
- Edit view/blade
- Upload gambar produk
- Dll.

### FASE 3: Testing

```bash
# Cek di browser apakah fitur berjalan
# http://127.0.0.1:8000

# Pastikan tidak ada error di terminal
```

### FASE 4: Commit & Push

```bash
# 1. Cek perubahan
git status

# 2. Review perubahan (opsional)
git diff

# 3. Tambahkan semua file yang berubah
git add .

# 4. ⚠️ JANGAN lupa tambahkan gambar jika ada
git add public/uploads/products/

# 5. Commit dengan pesan yang jelas dan deskriptif
git commit -m "feat: tambah fitur wishlist + halaman wishlist customer"

# 6. Push ke branch kamu
git push origin andikha    # atau: git push origin Rehan
```

### FASE 5: Merge ke Main (Ketika Fitur Sudah Stabil)

```bash
# 1. Pindah ke main
git checkout main

# 2. Pull terbaru (kalau teman sudah merge sesuatu)
git pull origin main

# 3. Merge branch kamu
git merge andikha    # atau: git merge Rehan

# 4. Jika ada CONFLICT, selesaikan dulu (lihat bagian Troubleshooting)

# 5. Push ke main
git push origin main

# 6. Beritahu teman: "Sudah merge ke main, tolong pull ya!"

# 7. Kembali ke branch kamu
git checkout andikha    # atau: git checkout Rehan
```

### FASE 6: Teman Pull Update

Teman kamu (misal Rehan) harus:
```bash
git checkout main
git pull origin main
git checkout Rehan
git merge main
php artisan migrate        # kalau ada migration baru
```

---

## 9. ❓ Troubleshooting / Masalah Umum

### ❌ Error: "Vite manifest not found"
**Penyebab:** `npm run dev` belum dijalankan  
**Solusi:**
```bash
npm run dev
```

### ❌ Error: "SQLSTATE[42S01]: Table already exists"
**Penyebab:** Migration sudah pernah dijalankan  
**Solusi:**
```bash
php artisan migrate:fresh
php artisan db:seed --class=AdminDemoSeeder
```

### ❌ Error: "SQLSTATE[HY000] [1049] Unknown database 'elvoapp'"
**Penyebab:** Database belum dibuat  
**Solusi:** Buat database `elvoapp` di phpMyAdmin/MySQL

### ❌ Gambar Produk Tidak Muncul
**Penyebab:** Gambar belum di-commit/di-pull  
**Solusi:**
```bash
# Yang punya gambar:
git add public/uploads/products/
git commit -m "chore: tambah gambar produk"
git push origin [branch-kamu]

# Yang mau ambil:
git pull origin main
```

### ❌ Git Conflict Saat Merge
**Penyebab:** Kamu dan teman edit file yang sama di baris yang sama  
**Solusi:**
1. Buka file yang conflict (ditandai `<<<<<<<` dan `>>>>>>>`)
2. Pilih versi yang benar, hapus marker conflict
3. Save file
4. Lalu:
```bash
git add .
git commit -m "fix: resolve merge conflict"
```

### ❌ Error: "Your local changes would be overwritten by merge"
**Solusi:**
```bash
# Simpan dulu perubahan sementara
git stash

# Pull/merge
git pull origin main

# Kembalikan perubahan
git stash pop
```

### ❌ npm run dev Error / node_modules Rusak
**Solusi:**
```bash
# Hapus node_modules dan install ulang
Remove-Item -Recurse -Force node_modules
npm install
npm run dev
```

---

## 10. 👤 Akun Demo

### Hierarki Role:
| Role | Akses |
|------|-------|
| 👑 **Owner** | Full akses — dashboard, manajemen admin, audit log, pengaturan sistem |
| 🛡️ **Admin** | Dashboard (terbatas), Produk, Pesanan, Transaksi, Pelanggan, Review |
| 👤 **Customer** | Hanya toko & pesanan sendiri |

### Owner:
| Field | Value |
|-------|-------|
| Email | `owner@elvo.com` |
| Password | `password` |
| Nama | Amin Owner |

### Admin:
| Nama | Email | Password |
|------|-------|----------|
| Admin Andikha | `admin1@elvoapp.com` | `password` |
| Rehan Admin | `admin2@elvoapp.com` | `password2` |

### Customer Demo (untuk test checkout):
| Nama | Email | Password |
|------|-------|----------|
| Test Customer | `testcus@elvo.com` | `password` |
| Siti Aminah | `siti@gmail.com` | `password` |
| Andi Wijaya | `andi@gmail.com` | `password` |
| Budi Santoso | `budi@gmail.com` | `password` |
| Rehan Pratama | `rehan@gmail.com` | `password` |
| Dewi Lestari | `dewi@gmail.com` | `password` |

---

## 📊 Format Pesan Commit yang Baik

Gunakan format berikut agar histori Git rapi:

```
feat: deskripsi       ← Fitur baru
fix: deskripsi        ← Perbaikan bug
chore: deskripsi      ← Maintenance (tambah gambar, update seeder, dll)
style: deskripsi      ← Perubahan tampilan/CSS
refactor: deskripsi   ← Refactor kode tanpa ubah fungsionalitas
docs: deskripsi       ← Perubahan dokumentasi
```

**Contoh:**
```
feat: tambah fitur keranjang belanja
fix: perbaiki total harga di checkout
chore: tambah gambar produk hoodie baru
style: update warna tombol checkout
docs: tambah panduan setup project
```

---

## 📝 Catatan Penting

1. **File `.env` TIDAK boleh di-push ke Git** — setiap orang punya `.env` sendiri dengan konfigurasi lokal masing-masing.
2. **Folder `vendor/` dan `node_modules/` TIDAK di-push** — setiap orang install sendiri dengan `composer install` dan `npm install`.
3. **Selalu komunikasi** sebelum merge ke `main` — beritahu teman kamu supaya tidak conflict.
4. **Backup sebelum `migrate:fresh`** — perintah ini menghapus SEMUA data di database.

---

> 📅 Dokumen ini dibuat: 8 Mei 2026  
> ✍️ Dibuat untuk tim ELVOAPP V2 (Andikha & Rehan)
