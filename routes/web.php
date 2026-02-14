<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Public routes (customer)
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
| Cart routes (customer)
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{service}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{service}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{service}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| Checkout routes (customer) - requires login
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
});

/*
|--------------------------------------------------------------------------
| Admin routes (requires login + role)
|--------------------------------------------------------------------------
| NOTE: role middleware assumes you already have RoleMiddleware working.
| If role middleware not ready yet, temporarily remove 'role:admin,developer'
*/
Route::middleware(['auth', 'role:admin,developer'])->group(function () {
    // Services admin CRUD
    Route::get('/admin/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/admin/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/admin/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/admin/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::patch('/admin/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('services.toggle');

    // Orders admin pages
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/admin/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/admin/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

require __DIR__.'/auth.php';
