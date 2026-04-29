<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// Tidak perlu Route::prefix lagi karena sudah diatur di bootstrap/app.php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Contoh rute lain kedepannya:
// URL-nya otomatis akan menjadi: /admin/products
// Route::get('/products', [ProductController::class, 'index'])->name('products');