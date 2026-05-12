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

// REVIEW
Route::post('/reviews', [ShopController::class, 'storeReview'])->name('reviews.store');

// API: Get products for cart
Route::get('/api/products', function () {
    return \App\Models\Product::select('id', 'name', 'price', 'stock', 'image', 'slug')
        ->where('stock', '>', 0)
        ->where('is_active', true)
        ->get();
})->name('api.products');

// API: All Categories (for search overlay default chips)
Route::get('/api/categories', function () {
    return \App\Models\Category::orderBy('name')->get()->map(fn($c) => [
        'id'   => $c->id,
        'name' => $c->name,
        'slug' => $c->slug,
        'url'  => route('shop.index', ['category' => $c->slug]),
    ]);
})->name('api.categories');

// API: Live Search — products + categories
Route::get('/api/search', function (\Illuminate\Http\Request $request) {
    $q = trim($request->get('q', ''));

    if (strlen($q) < 1) {
        return response()->json(['products' => [], 'categories' => []]);
    }

    $products = \App\Models\Product::with('category')
        ->where('stock', '>', 0)
        ->where('name', 'like', '%' . $q . '%')
        ->select('id', 'name', 'price', 'image', 'category_id', 'slug')
        ->limit(6)
        ->get()
        ->map(fn($p) => [
            'id'       => $p->id,
            'name'     => $p->name,
            'price'    => $p->price,
            'image'    => $p->image_url,
            'category' => $p->category?->name,
            'url'      => route('shop.index', ['search' => $p->name]),
        ]);

    $categories = \App\Models\Category::where('name', 'like', '%' . $q . '%')
        ->orWhere('slug', 'like', '%' . $q . '%')
        ->limit(4)
        ->get()
        ->map(fn($c) => [
            'id'   => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
            'url'  => route('shop.index', ['category' => $c->slug]),
        ]);

    return response()->json(compact('products', 'categories'));
})->name('api.search');
