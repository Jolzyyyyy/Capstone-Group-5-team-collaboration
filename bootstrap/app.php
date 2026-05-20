<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        /**
         * 1. GUEST REDIRECT:
         * Kapag HINDI logged in ang user, dito sila ibabato.
         * Naka-isolate ang Admin route (p-co-2026) para sa security.
         */
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('p-co-2026/*') || $request->is('p-co-2026')) {
                return route('admin.login');
            }
            return route('login');
        });

        /**
         * 2. AUTHENTICATED REDIRECT:
         * Ito ang modernong kapalit ng RedirectIfAuthenticated.
         * Kapag naka-login na ang user pero pinuntahan ang /login or /register,
         * itatapon sila sa 'dashboard.redirect' controller/route natin.
         */
        $middleware->redirectUsersTo(fn (Request $request) => route('dashboard.redirect'));

        /**
         * 3. MIDDLEWARE ALIASES:
         * FIX: In-align natin ang 'customer_otp' sa EnsureCustomerOtpIsVerified
         * para mag-match sa logic na inayos natin sa web.php.
         */
        $middleware->alias([
            'role'         => \App\Http\Middleware\RoleMiddleware::class,
            'admin'        => \App\Http\Middleware\AdminMiddleware::class,
            'admin.client.profile' => \App\Http\Middleware\EnsureAdminClientProfileIsComplete::class,
            
            // Ang 'customer_otp' alias na ngayon ang hahawak sa OTP Verification Logic
            'customer_otp' => \App\Http\Middleware\EnsureCustomerOtpIsVerified::class,
            'otp.verified' => \App\Http\Middleware\EnsureCustomerOtpIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

