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
         * Kung ang user ay HINDI logged in at sinubukang pumasok sa restricted page,
         * dito sila itatapon.
         */
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('p-co-2026/*') || $request->is('p-co-2026')) {
                return route('admin.login');
            }
            return route('login');
        });

        /**
         * 2. AUTHENTICATED REDIRECT:
         * Dito nagkakatalo. Gagamit tayo ng 'redirectUsersTo'.
         * Ito ay para LAMANG sa mga users na NAKA-LOGIN na.
         * Hindi nito gagalawin ang mga "Forgot Password" users.
         */
        $middleware->redirectUsersTo(fn (Request $request) => route('dashboard.redirect'));

        /**
         * 3. MIDDLEWARE ALIASES:
         */
        $middleware->alias([
            'role'          => \App\Http\Middleware\RoleMiddleware::class,
            'admin'         => \App\Http\Middleware\AdminMiddleware::class,
            'customer_otp'  => \App\Http\Middleware\CustomerOtpMiddleware::class, 
            'otp.verified'  => \App\Http\Middleware\EnsureCustomerOtpIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();