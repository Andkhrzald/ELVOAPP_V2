<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ShopController;
// TAMBAHKAN IMPORT INI AGAR POST LOGIN JALAN
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes - ELVOAPP_V2
|--------------------------------------------------------------------------
*/

// Halaman Utama
Route::get('/', function () {
    return view('customer.home');
})->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::get('/checkout/success', [ShopController::class, 'success'])->name('checkout.success');

// --- AUTH SYSTEM ---

// Login
Route::get('/login', [ShopController::class, 'login'])->name('login');
Route::post('/login', [ShopController::class, 'postLogin']); // Nama fungsi harus postLogin

// Register
Route::get('/register', [ShopController::class, 'register'])->name('register');
Route::post('/register', [ShopController::class, 'storeRegister'])->name('register.store'); // Nama fungsi harus storeRegister

// Logout
Route::post('/logout', [ShopController::class, 'logout'])->name('logout');
Route::get('/history', [ShopController::class, 'history'])->name('history.index');
