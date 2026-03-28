<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- CUSTOMER CONTROLLERS ---
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymongoCheckoutController;
use App\Http\Controllers\ProfileController;

// --- CUSTOMER AUTH CONTROLLERS (Auth/ folder) ---
use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyOtpController; 
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\SecurityController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Storefront)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
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
    Route::get('/register-7b5e93-adm-key', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register-7b5e93-adm-key', [AdminAuthController::class, 'register'])->name('admin.register.submit');
});

Route::middleware(['auth'])->prefix('p-co-2026/admin')->group(function () {
    Route::get('/verify-access', [AdminAuthController::class, 'showOtpForm'])->name('admin.otp.verify');
    
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('Admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/security/2fa', [SecurityController::class, 'show2faForm'])->name('admin.security.2fa');
        Route::post('/security/2fa/activate', [SecurityController::class, 'activate2fa'])->name('admin.security.2fa.activate');

        Route::resource('services-admin', ServiceController::class)->except(['index', 'show']);
        Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('services.toggle');
        Route::resource('orders', OrderController::class);

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

/*
|--------------------------------------------------------------------------
| 3. OTP FLOW (Shared: Auth & Guest)
|--------------------------------------------------------------------------
*/
Route::get('/verify-otp', [VerifyOtpController::class, 'show'])->name('customer.otp.verify');
Route::post('/verify-otp', [VerifyOtpController::class, 'verify'])->name('customer.otp.submit');
Route::post('/resend-otp', [VerifyOtpController::class, 'resend'])->name('customer.otp.resend');

/*
|--------------------------------------------------------------------------
| Authenticated routes (requires login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ✅ Dashboard (make sure this exists for login redirect)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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
| 5. GUEST & PASSWORD RECOVERY ROUTES
|--------------------------------------------------------------------------
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

/*
|--------------------------------------------------------------------------
| Auth routes (login/register/logout)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
