<?php

use Illuminate\Support\Facades\Route;

// CUSTOMER
Route::get('/', function () {
    $products = [
        ['id' => 1, 'name' => 'Jacket Zara', 'price' => 500000],
        ['id' => 2, 'name' => 'Shirt Zalora', 'price' => 250000],
        ['id' => 3, 'name' => 'Hoodie Uniqlo', 'price' => 300000],
    ];

    return view('customer.home', compact('products'));
})->name('home');

Route::get('/product/{id}', function ($id) {
    return view('customer.detail', compact('id'));
});