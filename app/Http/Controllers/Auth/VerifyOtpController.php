<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\OTPVerificationMail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VerifyOtpController extends Controller
{
    private const CUSTOMER_OTP_MAX_ATTEMPTS = 3;
    private const CUSTOMER_OTP_RESEND_MAX_ATTEMPTS = 1;

    public function showVerifyForm(Request $request)
    {
        return $this->show($request);
    }

    /**
     * Ipakita ang OTP verification form.
     * FIXED: Method name is 'show' to match the error requirement.
     */
    public function show(Request $request)
    {
        // 1. Kunin ang email mula sa session o authenticated user
        $email = session('otp_email') 
                 ?? session('password_reset_email') 
                 ?? $request->email 
                 ?? (Auth::check() ? Auth::user()->email : null);

        // 2. Kung walang mahanap na email, ibalik sa login
        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired or invalid request. Please try again.'
            ]);
        }

        // Siguraduhin na ang view file ay resources/views/auth/verify-otp.blade.php
        return view('auth.verify-otp', [
            'email' => $email,
            'verificationEmail' => session('is_forgot_password') === true
                ? session('password_reset_email')
                : $email,
        ]);
    }

    /**
     * Handle ang pag-verify ng OTP code.
     */
    public function verify(Request $request)
    {
        // Validation for the OTP input
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
            'verification_flow' => ['nullable', 'string'],
        ]);

        $otpThrottleKey = $this->customerOtpThrottleKey($request);

        $this->ensureRateLimit(
            $otpThrottleKey,
            self::CUSTOMER_OTP_MAX_ATTEMPTS,
            'otp'
        );

        $lookupEmail = session('is_forgot_password') === true
            ? session('password_reset_email')
            : $request->email;

        $user = User::where('email', trim((string) $lookupEmail))->first();

        if (!$user) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
                $this->throwOtpLockout($otpThrottleKey, 'otp');
            }

            return back()->withErrors(['otp' => 'Account not found.']);
        }

        // 1. Security Check: Tugma ba ang OTP?
        if (trim((string)$user->otp_code) !== trim((string)$request->otp)) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
                $this->throwOtpLockout($otpThrottleKey, 'otp');
            }

            return back()
                ->withInput()
                ->withErrors(['otp' => 'The security code you entered is incorrect.'])
                ->with('otp_attempt_count', min($attempts, self::CUSTOMER_OTP_MAX_ATTEMPTS));
        }

        // 2. Security Check: may valid expiry ba ang code?
        if (!$user->otp_expires_at || Carbon::parse($user->otp_expires_at)->isPast()) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
                $this->throwOtpLockout($otpThrottleKey, 'otp');
            }

            return back()->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        // 3. Mark as verified and Clean up OTP fields
        $user->forceFill([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        /**
         * 🛡️ FLOW REDIRECTION
         */

        // --- SCENARIO A: FORGOT PASSWORD FLOW ---
        if (session('is_forgot_password') === true) {
            $token = session('password_reset_token');
            $emailForReset = session('password_reset_email', $user->email);

            $request->session()->put('customer_otp_passed', true);
            $request->session()->forget(['is_forgot_password', 'otp_email']);
            
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $emailForReset
            ])->with('status', 'OTP Verified! Please set your new password below.');
        }

        // --- SCENARIO B: REGISTER / LOGIN FLOW ---
        if (!Auth::check()) {
            Auth::login($user);
        }

        $request->session()->regenerate();
        
        // MAHALAGA: Susi para makalampas sa 'customer_otp' middleware
        $request->session()->put('customer_otp_passed', true);
        
        // Linisin ang otp-related session data
        $request->session()->forget(['otp_email', 'password_reset_email', 'auth_type']);

        // Redirect sa Dashboard
        return redirect()->route('dashboard')->with('status', 'Verified successfully!');
    }

    /**
     * Resend ang OTP code sa user.
     */
    public function resend(Request $request)
    {
        $otpThrottleKey = $this->customerOtpThrottleKey($request);

        $this->ensureRateLimit(
            $otpThrottleKey,
            self::CUSTOMER_OTP_MAX_ATTEMPTS,
            'otp'
        );

        $this->ensureRateLimit(
            $this->customerOtpResendThrottleKey($request),
            self::CUSTOMER_OTP_RESEND_MAX_ATTEMPTS,
            'otp'
        );

        $lookupEmail = session('is_forgot_password') === true
            ? session('password_reset_email')
            : ($request->email ?? session('otp_email') ?? session('password_reset_email'));

        if (!$lookupEmail) {
            return back()->withErrors(['otp' => 'Email session expired. Please restart the process.']);
        }

        $user = User::where('email', $lookupEmail)->first();
        if (!$user) return back()->withErrors(['otp' => 'User not found.']);

        // Generate bagong 6-digit OTP na may leading zeros
        $otp = sprintf("%06d", mt_rand(0, 999999));
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        try {
            $deliveryEmail = session('is_forgot_password') === true
                ? session('otp_email', $user->email)
                : $user->email;

            Mail::to($deliveryEmail)->send(new OTPVerificationMail($otp));
            RateLimiter::hit(
                $this->customerOtpResendThrottleKey($request),
                User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS
            );

            return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
        } catch (\Exception $e) {
            Log::error("OTP Resend failed for {$user->email}: " . $e->getMessage());
            return back()->withErrors(['otp' => 'Failed to send code. Please check your internet connection.']);
        }
    }

    private function ensureRateLimit(string $key, int $maxAttempts, string $bag): void
    {
        if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return;
        }

        $this->throwOtpLockout($key, $bag);
    }

    private function throwOtpLockout(string $key, string $bag): never
    {
        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            $bag => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ]);
    }

    private function customerOtpThrottleKey(Request $request): string
    {
        return $this->customerOtpThrottleKeyFromContext($request->input('email', ''), $request->ip());
    }

    private function isForgotPasswordFlow(Request $request): bool
    {
        if (Auth::check()) {
            return false;
        }

        if (session('auth_type') === 'forgot_password') {
            return true;
        }

        return session('is_forgot_password') === true
            || $request->query('flow') === 'forgot_password'
            || $request->input('verification_flow') === 'forgot_password';
    }

    private function customerOtpResendThrottleKey(Request $request): string
    {
        $email = $request->input('email') ?? session('otp_email') ?? session('password_reset_email') ?? '';

        return $this->customerOtpResendThrottleKeyFromContext((string) $email, $request->ip());
    }

    private function customerOtpThrottleKeyFromContext(?string $email, string $ip): string
    {
        return 'customer-otp:' . Str::transliterate(Str::lower((string) $email) . '|' . $ip);
    }

    private function customerOtpResendThrottleKeyFromContext(?string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower((string) $email) . '|' . $ip);
    }
}
