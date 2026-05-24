<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\OwnerController;

// 1. Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');
Route::get('/dashboard/analytics-data', [DashboardController::class, 'getAnalyticsData'])->name('dashboard.analytics-data');

// 2. Products (CRUD Lengkap)
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::patch('/products/toggle-status/{id}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// 3. TRANSAKSI
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])->name('transaksi');

// 4. PESANAN MASUK
Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('pesanan-masuk');

// 5. MANAJEMEN STATUS PESANAN
Route::post('/orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');
Route::post('/orders/{id}/ship', [OrderController::class, 'ship'])->name('orders.ship');
Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');

// 6. KONFIRMASI / TOLAK PEMBATALAN
Route::post('/orders/{id}/confirm-cancel', [OrderController::class, 'confirmCancel'])->name('orders.confirm-cancel');
Route::post('/orders/{id}/reject-cancel', [OrderController::class, 'rejectCancel'])->name('orders.reject-cancel');

// 7. KONFIRMASI / TOLAK REFUND
Route::post('/orders/{id}/confirm-refund', [OrderController::class, 'confirmRefund'])->name('orders.confirm-refund');
Route::post('/orders/{id}/reject-refund', [OrderController::class, 'rejectRefund'])->name('orders.reject-refund');

// 8. PELANGGAN
Route::get('/pelanggan', [CustomerController::class, 'index'])->name('pelanggan');
Route::get('/pelanggan/{id}', [CustomerController::class, 'show'])->name('pelanggan.show');

// 9. REVIEW
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// 10. OWNER ONLY — Manajemen Admin & System
Route::middleware(['owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
    Route::get('/manage-admins', [OwnerController::class, 'manageAdmins'])->name('manage-admins');
    Route::post('/manage-admins/store', [OwnerController::class, 'storeAdmin'])->name('manage-admins.store');
    Route::delete('/manage-admins/{id}', [OwnerController::class, 'destroyAdmin'])->name('manage-admins.destroy');
    Route::get('/audit-log', [OwnerController::class, 'auditLog'])->name('audit-log');
});