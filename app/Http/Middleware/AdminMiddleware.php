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
        if (!Auth::check()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unauthorized access. This area is for approved staff and developers only.',
            ]);
        }

        if (!Auth::user()->canAccessAdminPortal()) {
            abort(403, 'Unauthorized access for ' . (Auth::user()->role ?? 'unknown role'));
        }

        if (Auth::user()->isDeveloper()) {
            $request->session()->put('staff_otp_passed', true);

            return $next($request);
        }

        $currentRoute = $request->route()->getName();
        $user = Auth::user();

        if (!$user->requiresStaffPortalOtp()) {
            $request->session()->put('staff_otp_passed', true);
            $request->session()->forget([
                'admin_auth_passed',
                'needs_email_otp',
                'admin_verified',
                '2fa_passed',
            ]);
        }

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
