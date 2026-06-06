<?php

use Illuminate\Support\Facades\Route;
// --- CUSTOMER CONTROLLERS ---
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymongoCheckoutController;
use App\Http\Controllers\ProfileController;

// --- ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminClientInvitationController;
use App\Http\Controllers\Admin\AdminClientProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeveloperAdminClientController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
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

Route::redirect('/home', '/')->name('landing.home');
Route::redirect('/products', '/services')->name('landing.products');
Route::redirect('/about', '/#about')->name('landing.about');
Route::redirect('/contact', '/#contact')->name('landing.contact');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::post('/paymongo/webhook', [PaymongoCheckoutController::class, 'webhook'])->name('payment.webhook');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{service}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{cartKey}', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{cartKey}', [CartController::class, 'remove'])->name('remove');
    Route::post('/sync', [CartController::class, 'syncCart'])->name('sync');
    Route::post('/buy-now', [CartController::class, 'buyNow'])->name('buy-now');
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
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
    Route::get('/admin-client-invite/{token}', [AdminClientInvitationController::class, 'show'])->name('admin-client-invitations.show');
    Route::post('/admin-client-invite/{token}', [AdminClientInvitationController::class, 'store'])->name('admin-client-invitations.store');
});

Route::middleware(['auth'])->prefix('p-co-2026/admin')->group(function () {
    Route::get('/verify-access', [AdminAuthController::class, 'showOtpForm'])->name('admin.otp.verify');
    Route::post('/verify-access', [AdminAuthController::class, 'verifyOtp'])->name('admin.otp.submit');
    Route::post('/verify-access/resend', [AdminAuthController::class, 'resendOtp'])->name('admin.otp.resend');
    
    Route::middleware(['role:admin_client,developer,admin', 'admin', 'admin.client.profile'])->group(function () {
        Route::get('/dashboard', AdminDashboardController::class)->name('admin.dashboard');

        Route::get('/security/2fa', [SecurityController::class, 'show2faForm'])->name('admin.security.2fa');
        Route::post('/security/2fa/activate', [SecurityController::class, 'activate2fa'])->name('admin.security.2fa.activate');

        Route::get('/reference-profile', [AdminClientProfileController::class, 'edit'])->name('admin.admin-client-profile.edit');
        Route::put('/reference-profile', [AdminClientProfileController::class, 'update'])->name('admin.admin-client-profile.update');

        // Admin services
        Route::get('/services', [ServiceController::class, 'adminIndex'])->name('admin.services.index');
        Route::middleware('role:developer,admin')->group(function () {
            Route::get('/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
            Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
            Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
            Route::put('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
            Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
            Route::patch('/services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('admin.services.toggle');
        });

        // Admin orders
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        Route::get('/customers', [AdminSectionController::class, 'customers'])->name('admin.customers.index');
        Route::get('/analytics', [AdminSectionController::class, 'analytics'])->name('admin.analytics.index');
        Route::get('/reports', [AdminSectionController::class, 'reports'])->name('admin.reports.index');
        Route::get('/settings', [AdminSectionController::class, 'settings'])->name('admin.settings.index');
        Route::get('/help-center', [AdminSectionController::class, 'help'])->name('admin.help.index');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

Route::middleware(['auth', 'role:developer', 'admin'])->prefix('p-co-2026/developer')->name('developer.')->group(function () {
    Route::get('/admin-clients', [DeveloperAdminClientController::class, 'index'])->name('admin-clients.index');
    Route::post('/admin-clients', [DeveloperAdminClientController::class, 'store'])->name('admin-clients.store');
    Route::patch('/admin-clients/{user}/approve', [DeveloperAdminClientController::class, 'approve'])->name('admin-clients.approve');
    Route::patch('/admin-clients/{user}/suspend', [DeveloperAdminClientController::class, 'suspend'])->name('admin-clients.suspend');
    Route::patch('/admin-clients/{user}/assign-customer', [DeveloperAdminClientController::class, 'assignCustomer'])->name('admin-clients.assign-customer');
});

/*
|--------------------------------------------------------------------------
| Customer routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer', 'otp.verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = request()->user();
        $orderQuery = $user->orders();

        return view('dashboard', [
            'recentOrders' => (clone $orderQuery)->latest()->limit(5)->get(),
            'assignedAdminClient' => $user->assignedAdminClient,
            'availableServices' => Service::where('is_active', true)->count(),
            'orderCount' => (clone $orderQuery)->count(),
            'activeOrderCount' => (clone $orderQuery)->whereIn('status', ['Pending', 'For Verification', 'Processing', 'Ready'])->count(),
            'completedOrderCount' => (clone $orderQuery)->where('status', 'Completed')->count(),
            'totalSpent' => (float) (clone $orderQuery)->sum('total_price'),
        ]);
    })->name('dashboard');

    // Checkout page
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // Place order
    Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');

    Route::get('/payment/checkout/{order?}', [PaymongoCheckoutController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/pay/{order}', [PaymongoCheckoutController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/success/{order}', [PaymongoCheckoutController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [PaymongoCheckoutController::class, 'cancel'])->name('payment.cancel');

    // Customer: My Orders pages
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'myShow'])->name('orders.my.show');

    Route::get('/notifications', [CustomerPortalController::class, 'notifications'])->name('customer.notifications');
    Route::get('/security', [CustomerPortalController::class, 'security'])->name('customer.security');
    Route::get('/settings', [CustomerPortalController::class, 'settings'])->name('customer.settings');
    Route::get('/help-center', [CustomerPortalController::class, 'help'])->name('customer.help');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/backup-email', [ProfileController::class, 'updateBackupEmail'])->name('profile.backup-email.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth routes (login/register/logout)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
