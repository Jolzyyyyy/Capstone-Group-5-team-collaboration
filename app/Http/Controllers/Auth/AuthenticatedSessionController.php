<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validate credentials and attempt login
        $request->authenticate();

        $user = Auth::user();

        /**
         * 🛡️ ADMIN GUARD
         * Proteksyon para sa PRINTIFY & CO. Admin.
         * Hindi pinapayagan ang admin role sa standard login.
         */
        if ($user->role === 'admin') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Admin accounts must login through the Admin Portal.',
            ]);
        }

        // 2. Regenerate session after successful credential check
        $request->session()->regenerate();

        // ✅ After login go to homepage
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Linisin ang lahat ng custom session markers bago mag-logout
        $request->session()->forget(['otp_passed', 'otp_email', 'auth_type']);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}