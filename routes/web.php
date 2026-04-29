<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ELVOAPP_V2
|--------------------------------------------------------------------------
*/

// Halaman Utama (Langsung manggil file di folder customer)
Route::get('/', function () {
    return view('customer.home');
})->name('home');
