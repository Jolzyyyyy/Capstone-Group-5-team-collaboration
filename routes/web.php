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
| 4. CUSTOMER SECTION (Authenticated & OTP Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard-redirect', function () {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.otp.verify');
        }
        return session('customer_otp_passed') === true 
            ? redirect()->route('dashboard') 
            : redirect()->route('customer.otp.verify');
    })->name('dashboard.redirect');

    Route::middleware(['customer_otp'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); 
        })->name('dashboard');

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
        });

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('customer.logout');
    });
});

/*
|--------------------------------------------------------------------------
| 5. GUEST & PASSWORD RECOVERY ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Forgot Password Request
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    // Reset Password Form (Dapat accessible as guest)
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
});

/**
 * FIXED: Inilabas natin ang POST request sa 'guest' middleware group.
 * Kapag nag-Auto Login ang user sa Controller, hindi na siya "Guest".
 * Kung naka-wrap ito sa 'guest', mag-rereload lang ang page dahil haharangin siya ni Laravel.
 */
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

require __DIR__ . '/auth.php';