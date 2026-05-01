<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ShopController;
/*
|--------------------------------------------------------------------------
| Web Routes - ELVOAPP_V2
|--------------------------------------------------------------------------
*/

// Halaman Utama (Langsung manggil file di folder customer)
Route::get('/', function () {
    return view('customer.home');
})->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::get('/checkout/success', [ShopController::class, 'success'])->name('checkout.success');
Route::get('/login', [ShopController::class, 'login'])->name('login');
Route::get('/register', [ShopController::class, 'register'])->name('register');
