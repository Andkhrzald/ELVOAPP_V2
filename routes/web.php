<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes - ELVOAPP_V2
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('customer.home'); })->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/success', [ShopController::class, 'success'])->name('checkout.success');
Route::get('/payment/{orderId}', [ShopController::class, 'payment'])->name('payment.page');
Route::get('/payment/{orderId}/check', [ShopController::class, 'checkPaymentStatus'])->name('payment.check');
Route::post('/payment/{orderId}/force-complete', [ShopController::class, 'forceComplete'])->name('payment.force-complete');

// AUTH
Route::get('/login', [ShopController::class, 'login'])->name('login');
Route::post('/login', [ShopController::class, 'postLogin']);
Route::get('/register', [ShopController::class, 'register'])->name('register');
Route::post('/register', [ShopController::class, 'storeRegister'])->name('register.store');
Route::post('/logout', [ShopController::class, 'logout'])->name('logout');

// PESANAN SAYA (aktif)
Route::get('/history', [ShopController::class, 'history'])->name('history.index');
Route::post('/orders/{id}/request-cancel', [ShopController::class, 'requestCancel'])->name('orders.request-cancel');
Route::post('/orders/{id}/request-refund', [ShopController::class, 'requestRefund'])->name('orders.request-refund');

// RIWAYAT TRANSAKSI (selesai/batal/refund)
Route::get('/riwayat', [ShopController::class, 'riwayat'])->name('riwayat.index');

// WISHLIST (max 20)
Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('wishlist.index');
Route::post('/wishlist/toggle', [ShopController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::post('/wishlist/move/{id}', [ShopController::class, 'moveToCart'])->name('wishlist.move');

// UPLOAD BUKTI BAYAR
Route::post('/orders/{id}/upload-proof', [ShopController::class, 'uploadProof'])->name('orders.upload-proof');

// REVIEW
Route::post('/reviews', [ShopController::class, 'storeReview'])->name('reviews.store');

// API: Get products for cart
Route::get('/api/products', function () {
    return \App\Models\Product::select('id', 'name', 'price', 'stock', 'image', 'slug')
        ->where('stock', '>', 0)
        ->where('is_active', true)
        ->get();
})->name('api.products');
