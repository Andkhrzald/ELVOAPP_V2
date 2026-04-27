use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;

// ROUTE UNTUK CUSTOMER (Halaman Depan)
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/product/{id}', [CustomerController::class, 'show'])->name('product.detail');

// ROUTE UNTUK ADMIN (Diberi prefix 'admin' agar URL-nya jadi /admin/dashboard)
Route::prefix('admin')->group(function () {
Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});