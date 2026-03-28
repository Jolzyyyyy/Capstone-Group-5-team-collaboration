<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerOtpMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. GUEST CHECK: Hayaan ang mga hindi naka-login
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 2. ROLE CHECK: Payagan ang admins na mag-bypass
        if ($user->role === 'admin') { 
            return $next($request);
        }

        /**
         * 3. ALLOWED ROUTES (Whitelist)
         * In-align natin ang mga pangalan dito sa routes/auth.php
         */
        $allowedRoutes = [
            'otp.verify',    // View OTP form
            'otp.submit',    // Submit OTP action
            'otp.resend',    // Resend OTP action
            'logout',        // Allow logout
            'password.reset', // Payagan ang reset password form
            'password.update'
        ];

        if ($request->routeIs($allowedRoutes)) {
            return $next($request);
        }

        /**
         * 4. FORGOT PASSWORD EXCEPTION
         * Kung ang user ay nasa gitna ng forgot password flow,
         * hayaan silang makarating sa OTP page nang hindi nire-redirect ng loop.
         */
        if (session('auth_type') === 'forgot_password') {
            return $next($request);
        }

        /**
         * 5. VERIFICATION LOGIC
         */
        $hasOtpPassed = session('customer_otp_passed') === true;

        if (!$hasOtpPassed) {
            // Siguraduhin na may email sa session para sa view
            if (!session()->has('otp_email')) {
                session(['otp_email' => $user->email]);
            }

            return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}