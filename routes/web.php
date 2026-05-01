<?php

use Illuminate\Support\Facades\Route;
// --- CUSTOMER CONTROLLERS ---
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymongoCheckoutController;
use App\Http\Controllers\ProfileController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DeveloperAdminClientController;
use App\Http\Controllers\Admin\SecurityController;
use App\Models\Service;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Storefront)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $services = Service::where('is_active', 1)
        ->with('activeVariations')
        ->get();

    return view('welcome', compact('services'));
})->name('home');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{service}', [CartController::class, 'add'])->name('add');
    Route::post('/remove/{service}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

/*
|--------------------------------------------------------------------------
| 2. ADMIN SECTION (Secret Routes: p-co-2026)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->prefix('p-co-2026')->group(function () {
    Route::get('/login-7b5e93-adm-key', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login-7b5e93-adm-key', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

Route::middleware(['auth'])->prefix('p-co-2026/admin')->group(function () {
    Route::get('/verify-access', [AdminAuthController::class, 'showOtpForm'])->name('admin.otp.verify');
    
    Route::middleware(['role:admin_client,developer,admin', 'admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/security/2fa', [SecurityController::class, 'show2faForm'])->name('admin.security.2fa');
        Route::post('/security/2fa/activate', [SecurityController::class, 'activate2fa'])->name('admin.security.2fa.activate');

        // Admin services
        Route::get('/services', [ServiceController::class, 'adminIndex'])->name('admin.services.index');
        Route::get('/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
        Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('admin.services.toggle');

        // Admin orders
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

Route::middleware(['auth', 'role:developer,admin', 'admin'])->prefix('p-co-2026/developer')->name('developer.')->group(function () {
    Route::get('/admin-clients', [DeveloperAdminClientController::class, 'index'])->name('admin-clients.index');
    Route::post('/admin-clients', [DeveloperAdminClientController::class, 'store'])->name('admin-clients.store');
    Route::patch('/admin-clients/{user}/approve', [DeveloperAdminClientController::class, 'approve'])->name('admin-clients.approve');
    Route::patch('/admin-clients/{user}/suspend', [DeveloperAdminClientController::class, 'suspend'])->name('admin-clients.suspend');
});

/*
|--------------------------------------------------------------------------
| Customer routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer', 'otp.verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/cart/sync', [CartController::class, 'sync'])->name('cart.sync');
    
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
| Auth routes (login/register/logout)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
