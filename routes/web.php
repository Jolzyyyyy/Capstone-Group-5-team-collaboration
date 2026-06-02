<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

// --- CUSTOMER CONTROLLERS ---
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

// --- CUSTOMER AUTH CONTROLLERS ---
use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyOtpController; 
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminClientInvitationController;
use App\Http\Controllers\Admin\AdminClientProfileController;
use App\Http\Controllers\Admin\DeveloperAdminClientController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Models\Service;

// --- THIRD PARTY AUTH CONTROLLERS ---
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Auth\FacebookController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (STAY)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/home'))->name('root');
Route::get('/home', [FrontPageController::class, 'home'])->name('home');
Route::get('/products', fn () => redirect('/services'))->name('landing.products');
Route::get('/about', [FrontPageController::class, 'about'])->name('landing.about');
Route::get('/contact', [FrontPageController::class, 'contact'])->name('landing.contact');
Route::get('/service-detail', [FrontPageController::class, 'serviceDetail'])->name('landing.service-detail');
Route::get('/service-details', [FrontPageController::class, 'serviceDetail'])->name('landing.service-details');
Route::get('/checkout', [FrontPageController::class, 'checkout'])->name('checkout.index');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{service}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{cartKey}', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{service}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

/*
|--------------------------------------------------------------------------
| 2. OTP & PASSWORD SETUP (STAY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    Route::get('/verify-account', [VerifyOtpController::class, 'showVerifyForm'])
        ->name('customer.otp.verify');

    Route::get('/verify-otp-page', [VerifyOtpController::class, 'showVerifyForm'])
        ->name('verify-account'); 

    Route::post('/verify-otp-submit', [VerifyOtpController::class, 'verify'])
        ->name('customer.otp.submit');

    Route::post('/resend-otp', [VerifyOtpController::class, 'resend'])
        ->name('customer.otp.resend');
    
    Route::get('/setup-password', [GoogleController::class, 'showSetupPassword'])->name('setup-password');
    Route::post('/setup-password-submit', [GoogleController::class, 'updateSetupPassword'])->name('password.setup.submit');

    Route::get('/dashboard-redirect', function () {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        if ($user->canAccessAdminPortal()) {
            return redirect()->route('admin.otp.verify');
        }
        
        if (session('customer_otp_passed') === true) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('customer.otp.verify');
    })->name('dashboard.redirect');
});

/*
|--------------------------------------------------------------------------
| 3. CUSTOMER SECURED SECTION (FIXED HOME REDIRECT)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer', 'customer_otp'])->group(function () {
    
    // FIXED: Ngayon, kapag kinlick ang HOME sa sidebar, babalik ito sa main landing page ('welcome')
    Route::get('/home', function () {
        return redirect()->route('home'); 
    })->name('customer.home');

    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // ROUTES PARA SA CUSTOMER PROFILE
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::patch('/backup-email', [ProfileController::class, 'updateBackupEmail'])->name('backup-email.update');
    });

    // --- FIXED ORDERS SECTION PARA SA CUSTOMER ---
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders');
    Route::get('/my-orders/{order}', [OrderController::class, 'myShow'])->name('my-orders.show');
    
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders.index');

    // 2. NOTIFICATIONS
    Route::get('/notifications', function() {
        return view('notifications'); 
    })->name('notifications');

    // 3. SETTINGS
    Route::get('/settings', function() {
        return view('settings'); 
    })->name('settings');

    // 4. SECURITY
    Route::get('/security', function() {
        return view('security'); 
    })->name('security');

    // 5. HELP CENTER
    Route::get('/help-center', function() {
        return view('help-center'); 
    })->name('help-center');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');
    Route::post('/cart/sync', [CartController::class, 'syncCart'])->name('cart.sync');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('customer.logout');
});

/*
|--------------------------------------------------------------------------
| 4. GUEST ROUTES (STAY)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.post');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| 5. ADMIN SECTION (STAY)
|--------------------------------------------------------------------------
*/

// Admin Login/Register Guest Routes
Route::middleware('guest')->prefix('p-co-2026')->group(function () {
    Route::get('/login-7b5e93-adm-key', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::get('/register-7b5e93-adm-key', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/login-7b5e93-adm-key', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/register-7b5e93-adm-key', [AdminAuthController::class, 'register'])->name('admin.register.submit');
    Route::get('/admin-client-invite/{token}', [AdminClientInvitationController::class, 'show'])->name('admin-client-invitations.show');
    Route::post('/admin-client-invite/{token}', [AdminClientInvitationController::class, 'store'])->name('admin-client-invitations.store');
});

// Admin Authenticated Routes
Route::middleware(['auth'])->prefix('p-co-2026/admin')->group(function () {
    
    // Phase 1: Email OTP
    Route::get('/verify-access', [AdminAuthController::class, 'showOtpForm'])->name('admin.otp.verify');
    Route::post('/verify-otp-submit', [AdminAuthController::class, 'verifyOtp'])->name('admin.otp.submit');
    Route::post('/resend-otp', [AdminAuthController::class, 'resendOtp'])->name('admin.otp.resend');
    
    // Phase 2: 2FA Security
    Route::get('/security-2fa', [SecurityController::class, 'show2faForm'])->name('admin.security.2fa');
    Route::post('/security-2fa-verify', [SecurityController::class, 'verify2fa'])->name('admin.security.2fa.activate');

    // Admin Dashboard & Resources
    Route::middleware(['role:admin,developer,admin_client'])->group(function () {
        
        // 1. DASHBOARD
        Route::get('/dashboard', AdminDashboardController::class)->name('admin.dashboard');

        // 2. CUSTOMER/USER
        Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
        
        // 3. ORDERS
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/orders-database', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders-database/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::middleware('role:developer,admin')->group(function () {
            Route::get('/orders-database/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
            Route::put('/orders-database/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
            Route::delete('/orders-database/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
        });
        
        // 4. PRODUCTS
        Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
        Route::get('/services', [ServiceController::class, 'adminIndex'])->name('admin.services.index');
        Route::get('/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
        Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('admin.services.toggle');
        Route::middleware('role:developer,admin')->group(function () {
            Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
        });
        
        // 5. RATES
        Route::get('/rates', [AdminController::class, 'rates'])->name('admin.rates');
        
        // 6. ANALYTICS
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
        
        // 7. REPORTS
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
        
        // 8. SETTINGS
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');

        // 9. HELP CENTER
        Route::get('/help-center', [AdminController::class, 'helpcenter'])->name('admin.helpcenter');
        Route::get('/section/customers', [AdminSectionController::class, 'customers'])->name('admin.customers.index');
        Route::get('/section/analytics', [AdminSectionController::class, 'analytics'])->name('admin.analytics.index');
        Route::get('/section/reports', [AdminSectionController::class, 'reports'])->name('admin.reports.index');
        Route::get('/section/settings', [AdminSectionController::class, 'settings'])->name('admin.settings.index');
        Route::get('/section/help-center', [AdminSectionController::class, 'help'])->name('admin.help.index');

        // PROFILE & LOGOUT
        Route::get('/profile', [ProfileController::class, 'adminEdit'])->name('admin.profile.edit');
        Route::patch('/profile', [ProfileController::class, 'adminUpdate'])->name('admin.profile.update');
        Route::get('/reference-profile', [AdminClientProfileController::class, 'edit'])->name('admin.admin-client-profile.edit');
        Route::put('/reference-profile', [AdminClientProfileController::class, 'update'])->name('admin.admin-client-profile.update');
        Route::resource('services-admin', ServiceController::class)->except(['index', 'show']);
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

Route::middleware(['auth', 'role:developer', 'admin'])->prefix('p-co-2026/developer')->name('developer.')->group(function () {
    Route::get('/orders', [AdminSectionController::class, 'orders'])->name('orders.index');
    Route::get('/services', [AdminSectionController::class, 'services'])->name('services.index');
    Route::get('/customers', [AdminSectionController::class, 'customers'])->name('customers.index');
    Route::get('/reports', [AdminSectionController::class, 'reports'])->name('reports.index');
    Route::get('/analytics', [AdminSectionController::class, 'analytics'])->name('analytics.index');
    Route::get('/settings', [AdminSectionController::class, 'settings'])->name('settings.index');
    Route::get('/admin-clients', [DeveloperAdminClientController::class, 'index'])->name('admin-clients.index');
    Route::post('/admin-clients', [DeveloperAdminClientController::class, 'store'])->name('admin-clients.store');
    Route::patch('/admin-clients/{user}/approve', [DeveloperAdminClientController::class, 'approve'])->name('admin-clients.approve');
    Route::patch('/admin-clients/{user}/suspend', [DeveloperAdminClientController::class, 'suspend'])->name('admin-clients.suspend');
    Route::patch('/admin-clients/{user}/assign-customer', [DeveloperAdminClientController::class, 'assignCustomer'])->name('admin-clients.assign-customer');
});

/*
|--------------------------------------------------------------------------
| 6. THIRD PARTY AUTH ROUTES (STAY)
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}

// Routes para sa CRUD operations ng Customers
Route::post('/admin/customers/save', [AdminController::class, 'saveCustomer'])->name('admin.customers.save');
Route::delete('/admin/customers/delete/{id}', [AdminController::class, 'deleteCustomer'])->name('admin.customers.delete');