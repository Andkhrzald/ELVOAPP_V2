<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\TransactionHistoryController;

// 1. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. Products
Route::get('/products', function () {
    return view('admin.products');
})->name('products');

// 3. TRANSAKSI (Logic: Langsung pakai Controller History)
// Kita ganti rute /history tadi menjadi /transaksi supaya URL-nya bagus
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])->name('transaksi');

// 4. PESANAN MASUK (Data Siti Aminah)
Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('pesanan-masuk');

// 5. PROSES KONFIRMASI
Route::post('/admin/orders/confirm/{id}', [OrderController::class, 'confirm'])->name('orders.confirm');