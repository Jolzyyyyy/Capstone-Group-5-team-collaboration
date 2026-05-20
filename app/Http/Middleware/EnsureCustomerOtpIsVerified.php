<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerOtpIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kunin ang kasalukuyang pangalan ng route
        $currentRoute = $request->route() ? $request->route()->getName() : null;

        // 1. ALLOW GUESTS ON FORGOT PASSWORD FLOW
        if ($request->session()->get('is_forgot_password') === true) {
            $forgotPasswordRoutes = [
                'customer.otp.verify',
                'customer.otp.submit',
                'customer.otp.resend',
                'password.reset',
                'password.update'
            ];

            if (in_array($currentRoute, $forgotPasswordRoutes)) {
                return $next($request);
            }
        }

        // 2. AUTH CHECK
        // Hayaan ang default 'auth' middleware ang humawak kung hindi naka-login
        if (!Auth::check()) {
            return $next($request); 
        }

        $user = Auth::user();

        // 3. CUSTOMER AREA GUARD
        if (!$user->isCustomer()) {
            abort(403, 'Unauthorized access for ' . ($user->role ?? 'unknown role'));
        }

        // 4. WHITELISTED ROUTES (Dito hindi haharang ang middleware)
        // Siguraduhin na ang lahat ng route names dito ay match sa routes/web.php
        $allowedRoutes = [
            'customer.otp.verify',
            'customer.otp.submit',
            'customer.otp.resend',
            'setup-password',
            'password.setup.submit',
            'customer.logout',
            'dashboard.redirect' 
        ];

        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // 5. OTP VERIFICATION CHECK
        if (session('customer_otp_passed') !== true) {

            // Backup check: Kung verified na sa DB at hindi naman bagong register, auto-pass
            if ($user->email_verified_at && session('auth_type') !== 'register') {
                session(['customer_otp_passed' => true]);
                return $next($request);
            }

            // Siguraduhin na may email sa session para sa view
            if (!session()->has('otp_email')) {
                session(['otp_email' => $user->email]);
            }

            /**
             * 🛡️ LOOP & ERROR PROTECTION: 
             * Inalis natin ang redirect sa 'verify-account' dahil 
             * ayon sa log mo, hindi ito existing (Route Not Defined).
             * Gagamitin natin ang tamang route name: 'customer.otp.verify'
             */
            if ($currentRoute === 'customer.otp.verify') {
                return $next($request);
            }

            // DITO ANG FIX: Pinalitan ang 'verify-account' ng 'customer.otp.verify'
            return redirect()->route('customer.otp.verify')
                ->withErrors(['otp' => 'Security verification required.']);
        }

        return $next($request);
    }
}

