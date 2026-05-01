<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 🛡️ REDIRECT IF AUTHENTICATED (Guest Middleware Override)
         * Kapag ang isang user ay naka-login na at sinubukan niyang i-access ang 
         * guest-only routes gaya ng /login, dito siya ididirekta ng Laravel.
         */
        RedirectIfAuthenticated::redirectUsing(function () {
            $user = Auth::user();

            if ($user) {
                /**
                 * 1. ADMIN FLOW (UNTOUCHED)
                 * Nanatiling original ang logic para sa Admin ayon sa iyong request.
                 */
                if (method_exists($user, 'canAccessAdminPortal') && $user->canAccessAdminPortal()) {
                    return route('admin.otp.verify');
                }

                /**
                 * 2. CUSTOMER FLOW
                 * Para sa mga customers ng PRINTIFY & CO., gagamit tayo ng 
                 * 'dashboard.redirect' route. Ang route na ito ang matalinong 
                 * magdedesisyon kung sa /dashboard o sa /verify-otp sila dapat mapunta.
                 */
                return route('dashboard.redirect');
            }

            // Default fallback kung walang user (very rare sa middleware na ito)
            return '/';
        });
    }
}
