<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. --- 🛡️ THE WALL: Check kung logged in at kung ADMIN talaga ---
        // Nanatiling intact: Kung hindi admin, logout at balik sa admin login.
        if (!Auth::check() || !Auth::user()->canAccessAdminPortal()) {
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unauthorized access. This area is for approved staff and developers only.'
            ]);
        }

        $user = Auth::user();
        $currentRoute = $request->route()->getName();

        /**
         * 2. --- 📧 STAGE 1: EMAIL OTP CHECK (Registration/Login Security) ---
         * Ginagamit ang 'needs_email_otp' marker para sa Admin flow.
         */
        if ($request->session()->get('needs_email_otp') === true) {
            // Check kung hindi pa verified ang admin (is_null email_verified_at)
            if (!$request->session()->has('admin_verified') && is_null($user->email_verified_at)) {
                $allowedOtpRoutes = [
                    'admin.otp.verify', 
                    'admin.otp.submit', 
                    'admin.otp.resend', 
                    'admin.logout'
                ];

                if (!in_array($currentRoute, $allowedOtpRoutes)) {
                    return redirect()->route('admin.otp.verify');
                }
                return $next($request);
            }
        }

        /**
         * 3. --- 📱 STAGE 2: GOOGLE 2FA (QR) CHECK ---
         * Mandatory step para sa lahat ng Admins.
         */
        if (!$request->session()->has('2fa_passed')) {
            $allowed2faRoutes = [
                'admin.security.2fa', 
                'admin.security.2fa.activate', 
                'admin.logout',
            ];

            // Proteksyon: Huwag papasukin sa Admin Dashboard hangga't walang 2FA verification
            if (!in_array($currentRoute, $allowed2faRoutes)) {
                return redirect()->route('admin.security.2fa');
            }
            return $next($request);
        }

        /**
         * 4. --- ✅ FINAL STAGE: FULL ADMIN ACCESS ---
         * Kapag verified na ang admin (Stage 1 & 2), bawal na bumalik sa login/verification pages.
         */
        $restrictedAfterVerification = [
            'admin.login', 
            'admin.otp.verify', 
            'admin.security.2fa'
        ];

        if (in_array($currentRoute, $restrictedAfterVerification)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
