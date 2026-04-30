<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController; 

// 1. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. Products
Route::get('/products', function () {
    return view('admin.products');
})->name('products');

// 3. Transaksi
Route::get('/transaksi', function () {
    return view('admin.transaksi');
})->name('transaksi');

// BIARKAN INI (KARENA INI YANG MENGAMBIL DATA SITI AMINAH)
Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('pesanan-masuk');

// 5. PROSES KONFIRMASI
Route::post('/orders/confirm/{id}', [OrderController::class, 'confirmShipping'])->name('orders.confirm');