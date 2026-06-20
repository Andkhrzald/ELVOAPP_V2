# 📘 PANDUAN PROJECT ELVOAPP V2

> **Nama Project:** ELVOAPP V2  
> **Repo GitHub:** https://github.com/Andkhrzald/ELVOAPP_V2.git  
> **Framework:** Laravel 13 + Vite + TailwindCSS v4 + Flowbite  
> **Database:** PostgreSQL (Supabase Cloud)  
> **Branch Aktif:** `main`, `andikha`, `Rehan`

---

## 📌 DAFTAR ISI

1. [Penjelasan Project](#1--penjelasan-project)
2. [Tech Stack & Struktur Folder](#2--tech-stack--struktur-folder)
3. [Cara Setup & Running Project (Pertama Kali)](#3--cara-setup--running-project-pertama-kali)
4. [Cara Running Sehari-hari](#4--cara-running-sehari-hari)
5. [Panduan Git Kolaborasi Tim](#5--panduan-git-kolaborasi-tim)
6. [Cara Agar Gambar Produk Muncul di Semua Laptop](#6--cara-agar-gambar-produk-muncul-di-semua-laptop)
7. [Cara Setup Database Online (Supabase)](#7--cara-setup-database-online-supabase)
8. [Cara Sync Database Antar Anggota Tim](#8--cara-sync-database-antar-anggota-tim)
9. [SOP: Alur Lengkap Push Fitur Baru](#9--sop-alur-lengkap-push-fitur-baru)
10. [Troubleshooting / Masalah Umum](#10--troubleshooting--masalah-umum)
11. [Akun Demo](#11--akun-demo)

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
| Database | PostgreSQL (Supabase Cloud) |
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
| Git | https://git-scm.com/download/win |

> 💡 **Rekomendasi:** Pakai **Laragon** karena sudah include PHP, Composer, dan otomatis setting PATH.

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

#### Step 6: Aktifkan Extension PostgreSQL di Laragon

Karena kita pakai **Supabase (PostgreSQL)**, Laragon perlu extension khusus.

1. Buka Laragon → klik **Menu** → **PHP** → **Extensions**
2. Centang **`pgsql`** dan **`pdo_pgsql`**
3. Restart Laragon (klik kanan → Restart)

> ✅ Kalau pakai XAMPP, buka `php.ini` lalu hapus `;` di depan `extension=pgsql` dan `extension=pdo_pgsql`, lalu restart Apache.

#### Step 7: Setup Database Online (Supabase)

Kita pakai **1 database online** yang bisa diakses berdua. **Tidak perlu install MySQL di lokal.**

1. **Andikha (cukup sekali):** Buka https://supabase.com → Login GitHub
2. Klik **New project**:
   - **Name:** `elvoapp`
   - **Database Password:** buat password kuat (catat!)
   - **Region:** Pilih **Singapore** (terdekat)
3. Tunggu ~2 menit sampai selesai
4. Masuk ke **Project Settings → Database → Connection string**
5. Copy string URI-nya, bentuknya:
   ```
   postgresql://postgres:******@db.xxxxxxxxxxx.supabase.co:5432/postgres
   ```
6. Edit file `.env` di project, isi seperti ini:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=password_yang_kamu_buat
```

> ⚠️ **Password dan host ini rahasia! Jangan di-commit ke Git!**

#### Step 8: Jalankan Migrasi Database (Cukup 1 Kali)

> **PENTING:** Karena database sudah online dan dipakai bareng, **cukup Andikha yang jalankan ini sekali saja**. Rehan TIDAK perlu migrate lagi.

```bash
php artisan migrate
```

#### Step 9: Jalankan Seeder (Data Demo) (Cukup 1 Kali)
```bash
php artisan db:seed --class=AdminDemoSeeder
php artisan db:seed --class=AnalyticsDemoSeeder
```

> Jika ada error `could not find driver`, berarti extension PostgreSQL belum aktif. Ulangi Step 6.

#### Step 10: Buat Symbolic Link untuk Storage (Opsional)
```bash
php artisan storage:link
```

#### Step 11: Jalankan Server! 🎉

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

#### Step 12: Buka di Browser
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

## 7. ☁️ Cara Setup Database Online (Supabase)

### Kenapa Pindah ke Supabase?

| Dulu (MySQL Lokal) | Sekarang (Supabase Cloud) |
|-------------------|--------------------------|
| Database di laptop masing-masing | **1 database di cloud** |
| Andikha punya data A, Rehan punya data B | **Data kalian SAMA PERSIS** |
| Kalau Andikha tambah produk, Rehan gak lihat | **Langsung kelihatan** |
| Ribet sinkronisasi tiap hari | **Tinggal colok, langsung jalan** |

### Cara Setting Pertama Kali (Cukup Andikha)

#### 1. Buat Akun Supabase

1. Buka **https://supabase.com**
2. Klik **"Start your project"** → Login pakai **GitHub**
3. Klik **"New project"**
4. Isi:
   - **Name:** `elvoapp`
   - **Database Password:** buat password (catat di notes!)
   - **Region:** Singapore 🌏
5. Klik **"Create new project"** — tunggu ~2 menit

#### 2. Ambil String Koneksi

1. Di dashboard Supabase, klik **Project Settings → Database**
2. Cari bagian **"Connection string"** → pilih tab **"URI"**
3. Copy seluruh string, bentuknya seperti ini:
   ```
   postgresql://postgres:******@db.xxxxxxxxxxx.supabase.co:5432/postgres
   ```

#### 3. Setup Laragon (Andikha & Rehan masing-masing)

1. Buka Laragon → klik kanan → **Tools → Quick Settings → PHP Extensions**
2. Centang **pgsql** dan **pdo_pgsql**
3. Restart Laragon

> **Alternatif XAMPP:** Buka `php.ini`, cari `;extension=pgsql` dan `;extension=pdo_pgsql`, hapus tanda `;` di depannya, lalu restart Apache.

#### 4. Update File `.env`

Edit `.env` di root project:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxxxxxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=[password_kamu]
```

#### 5. Jalankan Migrasi (Andikha aja)

```bash
php artisan migrate
```

#### 6. Jalankan Seeder (Andikha aja)

```bash
php artisan db:seed --class=AdminDemoSeeder
php artisan db:seed --class=AnalyticsDemoSeeder
```

### Cara Setting Rehan (Cukup 2 Langkah)

1. **Aktifkan extension pgsql** di Laragon (sama seperti Step 3)
2. **Isi `.env`** dengan host & password yang sama dari Supabase
3. Selesai ✅ — **Rehan TIDAK perlu migrate/seed lagi**

### Cara Cek Database Online

Buka **supabase.com** → login → klik project `elvoapp`:
- **Table Editor:** Lihat isi tabel seperti Excel
- **SQL Editor:** Jalankan query SQL
- **Database:** Cek koneksi, backup, dll

---

## 8. 🗄️ Cara Sync Database — Karena Udah 1 Database Online

### Kabar Baik 🎉

Karena kalian sekarang pakai **1 database online (Supabase)**, sync database jadi **JAUH LEBIH MUDAH**:

| Situasi | Cara |
|---------|------|
| Andikha tambah produk | Rehan langsung lihat ✅ |
| Rehan bikin order | Andikha langsung lihat ✅ |
| Ada perubahan status | Semua realtime ✅ |

### Kapan Perlu Migrasi?

Migrasi cuma perlu kalau ada **perubahan struktur tabel** (nambah kolom, tabel baru).

#### Yang Buat Perubahan (misal Andikha):
```bash
# 1. Buat file migration
php artisan make:migration add_wishlist_to_users_table

# 2. Edit file migration
# 3. Jalankan migration (langsung ke database online)
php artisan migrate

# 4. Commit & Push
git add database/migrations/
git commit -m "feat: tambah kolom wishlist di users"
git push origin andikha
```

#### Yang Menerima (Rehan):
```bash
# 1. Pull update dari Git
git checkout main
git pull origin main
git checkout Rehan
git merge main

# 2. Data di Supabase udah otomatis update oleh Andikha
#    Rehan TIDAK perlu migrate lagi ✅
```

> ⚠️ **Bedanya sama dulu:**
> - **Dulu (MySQL lokal):** Setiap orang harus `php artisan migrate` sendiri
> - **Sekarang (Supabase):** Cukup **satu orang** yang migrate, database online langsung berubah

### Kalau Mau Tambah Data Demo

Karena database 1, kalau Andikha jalanin seeder, **data langsung masuk** dan Rehan langsung lihat.

```bash
# Andikha jalanin (cukup sekali):
php artisan db:seed --class=NamaSeeder
```

### Hati-hati dengan `migrate:fresh`! ⚠️

```bash
# ⚠️ INI BERBAHAYA! Akan HAPUS SEMUA DATA di database ONLINE!
php artisan migrate:fresh
```

Karena database online dipakai berdua, **jangan sembarangan** jalanin `migrate:fresh`. Pastikan:
- Semua orang tau
- Data penting udah di-backup
- Baru jalanin setelah diskusi

---

## 9. 📋 SOP: Alur Lengkap Push Fitur Baru

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
# ⚠️ Dengan Supabase: Kalau teman udah migrate, kamu TIDAK perlu migrate lagi
```

---

## 10. ❓ Troubleshooting / Masalah Umum

### ❌ Error: "Vite manifest not found"
**Penyebab:** `npm run dev` belum dijalankan  
**Solusi:**
```bash
npm run dev
```

### ❌ Error: "could not find driver"
**Penyebab:** Extension PostgreSQL belum aktif di PHP  
**Solusi:**
1. Laragon: Menu → PHP → Extensions → centang **pgsql** + **pdo_pgsql** → restart
2. XAMPP: Edit `php.ini`, hapus `;` di depan `extension=pgsql` dan `extension=pdo_pgsql` → restart Apache

### ❌ Error: "SQLSTATE[42S01]: Table already exists"
**Penyebab:** Migration sudah pernah dijalankan  
**Solusi:**
```bash
# ⚠️ HATI-HATI: Ini hapus SEMUA data di database online!
php artisan migrate:fresh
php artisan db:seed --class=AdminDemoSeeder
php artisan db:seed --class=AnalyticsDemoSeeder
```

### ❌ Error: "Connection refused" atau "Timeout"
**Penyebab:** Database Supabase sedang mati / internet bermasalah  
**Solusi:**
1. Cek internet kamu
2. Buka https://supabase.com → cek status database
3. Database Supabase free bisa "pause" kalau gak dipakai 7 hari. Buka dashboard Supabase untuk mengaktifkan kembali

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

## 11. 👤 Akun Demo

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
| Nama | Admin Owner |

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
4. **Backup sebelum `migrate:fresh`** — perintah ini menghapus SEMUA data di database online!
5. **Database online (Supabase) bisa "pause"** setelah 7 hari tidak dipakai. Buka dashboard Supabase dan jalanin query apa saja untuk mengaktifkannya lagi.
6. **Password Supabase simpan di `.env`** — jangan pernah di-commit ke Git!
7. **Pastikan extension pgsql aktif** — kalau error "could not find driver", aktifkan pgsql & pdo_pgsql di Laragon.

---

> 📅 Dokumen ini dibuat: 8 Mei 2026  
> ✍️ Dibuat untuk tim ELVOAPP V2 (Andikha & Rehan)
