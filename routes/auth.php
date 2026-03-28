<?php

use Illuminate\Support\Facades\Route;

/**
 * 🛠️ AUTH CONTROLLERS (App\Http\Controllers\Auth)
 */
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyOtpController; 
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;

/**
 * 🛠️ ADMIN AUTH CONTROLLERS
 */
use App\Http\Controllers\Admin\Auth\AdminAuthController;

/*
|--------------------------------------------------------------------------
| 1. GUEST AREA (Users not logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Admin Register (Secret)
    Route::get('p-co-2026/register-7b5e93-adm-key', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('p-co-2026/register-7b5e93-adm-key', [AdminAuthController::class, 'register'])->name('admin.register.submit');

    // Customer Register & Login
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Recovery (Forgot Password Flow)
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    // Reset Password Form (Dito papasok ang user galing sa Email Link)
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    
    /**
     * DITO ANG UPDATE: 
     * In-align natin ang POST request sa PasswordController@update 
     * para gumana ang 'password.update' name na gusto mo sa Blade.
     */
    Route::post('reset-password', [PasswordController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED AREA (Users already logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // THE REDIRECTOR (PRINTIFY & CO. Logic)
    Route::get('/dashboard-redirect', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.otp.verify');
        }
        
        // Check kung nakapasa na sa OTP ang customer
        return session('customer_otp_passed') === true 
            ? redirect()->route('dashboard') 
            : redirect()->route('otp.verify');
    })->name('dashboard.redirect');

    // --- OTP Flow ---
    Route::prefix('verify-account')->name('otp.')->group(function () {
        Route::get('/', [VerifyOtpController::class, 'show'])->name('verify');   
        Route::post('/', [VerifyOtpController::class, 'verify'])->name('submit'); 
        Route::post('/resend', [VerifyOtpController::class, 'resend'])->name('resend'); 
    });

    // --- Admin OTP Flow ---
    Route::prefix('admin-auth')->name('admin.otp.')->group(function () {
        Route::get('/verify', [AdminAuthController::class, 'showOtpForm'])->name('verify');
        Route::post('/verify', [AdminAuthController::class, 'verifyOtp'])->name('submit');
        Route::post('/resend', [AdminAuthController::class, 'resendOtp'])->name('resend');
    });

    // Security & Password Management inside Profile
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    
    /**
     * ALIGNMENT:
     * Para kahit sa loob ng settings (Authenticated) o sa Reset form (Guest),
     * iisang logic lang ang gagamitin ni PasswordController.
     */
    Route::put('password-change', [PasswordController::class, 'update'])->name('password.change');
    
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});