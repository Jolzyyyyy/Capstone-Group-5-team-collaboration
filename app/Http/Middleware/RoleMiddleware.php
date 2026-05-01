<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request for Printify & Co.
     * * Sinisiguro nito na hindi maghahalo ang access ng Admin at Customer.
     * Usage in routes:
     * ->middleware('role:admin')
     * ->middleware('role:customer')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. AUTH CHECK: Siguraduhin na ang user ay logged in
        if (! Auth::check()) {
            /**
             * 🛡️ SMART REDIRECTION
             * Kung ang sinusubukang i-access ay ang Admin route (p-co-2026),
             * ibalik sila sa Admin Login. Kung hindi, sa Customer Login.
             */
            if ($request->is('p-co-2026/*') || $request->is('p-co-2026')) {
                return redirect()->route('admin.login');
            }
            
            return redirect()->route('login');
        }

        $user = Auth::user();

        /**
         * 2. ROLE AUTHORIZATION
         * Chine-check kung ang user role (admin o customer) ay kasama sa 
         * pinapayagan para sa specific route na ito.
         */
        if (!$user->role || !in_array($user->role, $roles)) {
            // Kung Admin na pumasok sa Customer dashboard, o vice-versa: 403 Forbidden.
            abort(403, 'Unauthorized access for ' . ($user->role ?? 'unknown role'));
        }

        if ($user->isAdminClient() && !$user->isApprovedAdminClient()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'This staff account is not yet approved for portal access.',
            ]);
        }

        // 3. Allow access kung match ang role
        return $next($request);
    }
}
