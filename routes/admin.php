<?php

use Illuminate\Support\Facades\Route;

// ADMIN
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});