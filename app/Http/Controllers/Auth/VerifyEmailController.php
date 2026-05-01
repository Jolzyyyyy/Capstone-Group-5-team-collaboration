<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Show OTP input form
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        // ANTI-LOOP: kung verified na at session marker, diretso dashboard
        if ($user->hasVerifiedEmail() && session('customer_otp_passed') === true) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.verify-otp', [
            'email' => $user->email,
        ]);
    }

    /**
     * Verify OTP input
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Validate OTP (6 digits)
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        // Check OTP match
        if (trim((string)$user->otp_code) === trim((string)$request->otp_code)) {

            // Check expiration
            if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
                return back()->withErrors([
                    'otp_code' => 'The code has expired. Please request a new one.'
                ]);
            }

            // Update DB: mark as verified
            $user->forceFill([
                'email_verified_at' => Carbon::now(),
                'otp_code' => null,
                'otp_expires_at' => null,
            ])->save();

            // Unlock session for dashboard access
            $request->session()->put('customer_otp_passed', true);
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('status', 'Account verified successfully! Welcome to Printify & Co.');
        }

        return back()->withErrors([
            'otp_code' => 'The verification code is incorrect.'
        ]);
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Generate new OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        // Send OTP email
        try {
            $user->notify(new SendOTP($otp));
        } catch (\Exception $e) {
            Log::error("Resend OTP failed: " . $e->getMessage());
            return back()->withErrors([
                'otp_code' => 'Failed to resend OTP. Please try again later.'
            ]);
        }

        return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
    }
}
