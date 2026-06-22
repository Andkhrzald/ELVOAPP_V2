<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
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
Route::delete('/products/images/{id}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
Route::post('/products/images/{id}/primary', [ProductController::class, 'setPrimaryImage'])->name('products.images.primary');
Route::delete('/products/variants/{id}', [ProductController::class, 'destroyVariant'])->name('products.variants.destroy');

// 3. Categories (admin & owner)
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// 4. TRANSAKSI
Route::get('/transaksi', [TransactionHistoryController::class, 'index'])->name('transaksi');

// 5. PESANAN MASUK
Route::get('/pesanan-masuk', [OrderController::class, 'index'])->name('pesanan-masuk');
Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');

// 6. KONFIRMASI PEMBAYARAN (pending → proses)
Route::post('/orders/{id}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
Route::get('/orders/{id}/payment-proof', [OrderController::class, 'viewProof'])->name('orders.payment-proof');

// 7. MANAJEMEN STATUS PESANAN
Route::post('/orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');
Route::post('/orders/{id}/ship', [OrderController::class, 'ship'])->name('orders.ship');
Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');

// 7. KONFIRMASI / TOLAK PEMBATALAN
Route::post('/orders/{id}/confirm-cancel', [OrderController::class, 'confirmCancel'])->name('orders.confirm-cancel');
Route::post('/orders/{id}/reject-cancel', [OrderController::class, 'rejectCancel'])->name('orders.reject-cancel');

// 8. KONFIRMASI / TOLAK REFUND
Route::post('/orders/{id}/confirm-refund', [OrderController::class, 'confirmRefund'])->name('orders.confirm-refund');
Route::post('/orders/{id}/reject-refund', [OrderController::class, 'rejectRefund'])->name('orders.reject-refund');

// 9. PELANGGAN
Route::get('/pelanggan', [CustomerController::class, 'index'])->name('pelanggan');
Route::get('/pelanggan/{id}', [CustomerController::class, 'show'])->name('pelanggan.show');

// 10. REVIEW
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

// 11. EXPORT (admin level)
Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
Route::get('/transaksi/export', [TransactionHistoryController::class, 'export'])->name('transaksi.export');
Route::get('/pelanggan/export', [CustomerController::class, 'export'])->name('pelanggan.export');

// 12. OWNER ONLY — Manajemen Admin, System, Laporan
Route::middleware(['owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
    Route::get('/manage-admins', [OwnerController::class, 'manageAdmins'])->name('manage-admins');
    Route::post('/manage-admins/store', [OwnerController::class, 'storeAdmin'])->name('manage-admins.store');
    Route::delete('/manage-admins/{id}', [OwnerController::class, 'destroyAdmin'])->name('manage-admins.destroy');
    Route::get('/audit-log', [OwnerController::class, 'auditLog'])->name('audit-log');

    // Laporan Keuangan
    Route::get('/financial-reports', [OwnerController::class, 'financialReports'])->name('financial-reports');
    Route::get('/financial-reports/export', [OwnerController::class, 'exportFinancial'])->name('financial-reports.export');

    // Laporan Produk
    Route::get('/product-reports', [OwnerController::class, 'productReports'])->name('product-reports');

    // Riwayat Stok
    Route::get('/stock-history', [OwnerController::class, 'stockHistory'])->name('stock-history');
    Route::get('/stock-history/export', [OwnerController::class, 'exportStock'])->name('stock-history.export');
});

// 13. PENGATURAN TOKO — Visible all admin, editable only owner
Route::get('/settings', [OwnerController::class, 'settings'])->name('settings');
Route::post('/settings', [OwnerController::class, 'updateSettings'])->name('settings.update');
