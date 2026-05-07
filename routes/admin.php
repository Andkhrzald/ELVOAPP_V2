<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\Admin\ProductController;

// 1. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. Products (CRUD Lengkap)
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// 3. TRANSAKSI — Riwayat Semua Transaksi
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])->name('transaksi');

// 4. PESANAN MASUK
Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('pesanan-masuk');

// 5. MANAJEMEN STATUS PESANAN (Flow Profesional)
// pending → proses (Konfirmasi Pesanan)
Route::post('/orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');

// proses → dikirim (Input Resi & Kirim)
Route::post('/orders/{id}/ship', [OrderController::class, 'ship'])->name('orders.ship');

// dikirim → selesai (Tandai Selesai)
Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');

// any → batal (Batalkan Pesanan)
Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');