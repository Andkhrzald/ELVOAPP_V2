<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// URL: /admin/dashboard | Nama: admin.dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// URL: /admin/products | Nama: admin.products
Route::get('/products', function () {
    return view('admin.products');
})->name('products'); 

// URL: /admin/transaksi | Nama: admin.transaksi
Route::get('/transaksi', function () {
    return view('admin.transaksi'); 
})->name('transaksi');

// Sesuaikan dengan nama Controller yang kamu gunakan
Route::get('/admin/pesanan-masuk', function () {
    return view('admin.pesanan-masuk'); // Pastikan file blade kamu namanya pesanan-masuk.blade.php
})->name('pesanan-masuk');

/**
 * CATATAN PENTING:
 * Jika di bootstrap/app.php Anda menggunakan ->withRouting(then: ...) 
 * dengan group name 'admin.', maka ->name('products') otomatis menjadi 'admin.products'.
 * * Jika ternyata belum otomatis, ubah ->name('products') menjadi ->name('admin.products') dst.
 */