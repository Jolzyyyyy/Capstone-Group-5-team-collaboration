<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->canAccessAdminPortal()) {
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unauthorized access. This area is for approved staff and developers only.',
            ]);
        }

        $currentRoute = $request->route()->getName();

        if (!$request->session()->has('staff_otp_passed')) {
            $allowedOtpRoutes = [
                'admin.otp.verify',
                'admin.otp.submit',
                'admin.otp.resend',
                'admin.logout',
            ];

            if (!in_array($currentRoute, $allowedOtpRoutes, true)) {
                return redirect()->route('admin.otp.verify');
            }

            return $next($request);
        }

        $restrictedAfterVerification = [
            'admin.login',
            'admin.otp.verify',
            'admin.security.2fa',
            'admin.security.2fa.activate',
        ];

        if (in_array($currentRoute, $restrictedAfterVerification, true)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
