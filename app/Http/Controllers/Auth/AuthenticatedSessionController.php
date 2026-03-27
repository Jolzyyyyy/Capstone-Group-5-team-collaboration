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

        // 3. Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // 4. Send OTP Email Notification
        try {
            $user->notify(new SendOTP($otp));
        } catch (\Exception $e) {
            Log::error('Login OTP Email failed: ' . $e->getMessage());

            // Logout user if email fails to prevent unauthorized access
            Auth::guard('web')->logout();
            
            return back()->withErrors([
                'email' => 'Failed to send verification code. Please check your connection and try again.',
            ]);
        }

        /**
         * 5. Session Markers (OTP Lock)
         * Gagamitin ito ng iyong middleware para pigilan ang user
         * na makapasok sa dashboard hangga't 'otp_passed' is false.
         */
        $request->session()->put([
            'otp_passed' => false, 
            'otp_email' => $user->email,
            'auth_type' => 'login',
        ]);

        // 6. Redirect to OTP verification page
        return redirect()->route('otp.verify')
            ->with('status', 'A 6-digit verification code has been sent to your email.');
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