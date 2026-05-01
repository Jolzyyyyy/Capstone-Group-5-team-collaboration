<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the forgot password view.
     */
    public function create(): View
    {
        // Tumuturo sa resources/views/auth/forgot-password.blade.php
        return view('auth.forgot-password');
    }

    /**
     * Handle password reset request (OTP-based).
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate email input
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 2. Find user by email
        $user = User::where('email', trim($request->email))->first();

        // Security Note: Generic error message para sa privacy ng users
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => __("We can't find a user with that email address."),
                ]);
        }

        // 3. Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        /** * 4. Generate standard Laravel reset token
         * Gagamitin natin ito mamaya sa final step ng password reset para sa security verification.
         */
        $token = Password::createToken($user);

        // 5. Save OTP to Database
        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        // 6. Send OTP email notification
        try {
            /** * IMPORTANT: Siguraduhin na ang SendOTP notification mo ay 
             * walang 'implements ShouldQueue' para mag-send agad.
             */
            $user->notify(new SendOTP($otp));
            RateLimiter::hit($this->customerOtpResendThrottleKey($user->email, $request->ip()), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);
            
        } catch (\Exception $e) {
            Log::error('Forgot Password OTP Email failed for ' . $user->email . ': ' . $e->getMessage());

            return back()->withErrors([
                'email' => 'Failed to send verification code. Please try again.',
            ]);
        }

        /**
         * 7. Store session data (CRITICAL)
         * Inilalagay natin ang mga flags para malaman ng VerifyOtpController 
         * na Password Recovery ang ginagawa ng user, hindi regular login.
         */
        $request->session()->put([
            'password_reset_token' => $token,
            'password_reset_email' => $user->email,
            'otp_email'           => $user->email,
            'is_forgot_password'  => true, // 🔥 Eto ang trigger para sa Reset Password redirection
            'auth_type'           => 'forgot_password', 
        ]);

        /**
         * 8. Redirect to OTP verification page
         */
        return redirect()->route('otp.verify', [
                'email' => $user->email,
                'flow' => 'forgot_password',
            ])
            ->with('status', 'A 6-digit verification code has been sent to your email.');
    }

    private function customerOtpResendThrottleKey(string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower($email) . '|' . $ip);
    }
}
