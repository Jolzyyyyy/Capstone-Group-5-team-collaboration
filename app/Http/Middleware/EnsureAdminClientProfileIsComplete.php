<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminClientProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->isApprovedAdminClient() || $user->hasCompletedAdminClientProfile()) {
            return $next($request);
        }

        $allowedRoutes = [
            'admin.admin-client-profile.edit',
            'admin.admin-client-profile.update',
            'admin.logout',
        ];

        if ($request->routeIs($allowedRoutes)) {
            return $next($request);
        }

        return redirect()
            ->route('admin.admin-client-profile.edit')
            ->withErrors([
                'business_name' => 'Please complete the admin client reference profile before continuing.',
            ]);
    }
}
