<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;

// ROUTE UNTUK CUSTOMER (Halaman Depan)
Route::get('/', function () {
    return view('customer.home'); // Pastikan file home.blade.php sudah ada di folder views/customer
})->name('home');

// ROUTE UNTUK ADMIN
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Pastikan file dashboard.blade.php sudah ada di folder views/admin
    })->name('admin.dashboard');
});
