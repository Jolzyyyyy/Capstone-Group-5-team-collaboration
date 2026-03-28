<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Customer-facing services (browse & view)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

/*
|--------------------------------------------------------------------------
| Cart routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{service}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{cartKey}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Keep dashboard under auth for now to avoid login redirect issues
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Customer routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {

    // Checkout page
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // Place order (ZIP required) — MUST MATCH checkout form route('checkout.place')
    Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');

    // Customer: My Orders pages
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'myShow'])->name('orders.my.show');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,developer'])->group(function () {

    // Services admin pages
    Route::get('/admin/services', [ServiceController::class, 'adminIndex'])
        ->name('services.admin.index');

    Route::get('/admin/services/create', [ServiceController::class, 'create'])
        ->name('services.create');

    Route::post('/admin/services', [ServiceController::class, 'store'])
        ->name('services.store');

    Route::get('/admin/services/{service}/edit', [ServiceController::class, 'edit'])
        ->name('services.edit');

    Route::put('/admin/services/{service}', [ServiceController::class, 'update'])
        ->name('services.update');

    Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy'])
        ->name('services.destroy');

    Route::patch('/admin/services/{service}/toggle', [ServiceController::class, 'toggleActive'])
        ->name('services.toggle');

    // Orders admin pages
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/admin/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/admin/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth routes (login/register/logout)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';