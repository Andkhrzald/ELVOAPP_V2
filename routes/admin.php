<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\Admin\ProductController;

// 1. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. Products (CRUD Lengkap)
Route::get('/products', [ProductController::class, 'index'])->name('products'); // Tampil Tabel
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store'); // Simpan Produk Baru
Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit'); // Form Edit
Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update'); // Proses Update
Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy'); // Proses Hapus

// 3. TRANSAKSI (Logic: Langsung pakai Controller History)
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])->name('transaksi');

// 4. PESANAN MASUK (Data Siti Aminah)
Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('pesanan-masuk');

// 5. PROSES KONFIRMASI
Route::post('/admin/orders/confirm/{id}', [OrderController::class, 'confirm'])->name('orders.confirm');