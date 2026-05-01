<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VerifyOtpController extends Controller
{
    private const CUSTOMER_OTP_MAX_ATTEMPTS = 3;
    private const CUSTOMER_OTP_RESEND_MAX_ATTEMPTS = 1;

    /**
     * Display the OTP verification form.
     */
    public function show(Request $request)
    {
        // Kunin ang email mula sa session flags
        $email = session('otp_email') 
                 ?? session('password_reset_email') 
                 ?? $request->email 
                 ?? (Auth::check() ? Auth::user()->email : null);

        $verificationFlow = session('is_forgot_password') === true || $request->query('flow') === 'forgot_password'
            ? 'forgot_password'
            : 'account_verification';

        $otpThrottleKey = $this->customerOtpThrottleKeyFromContext($email, $request->ip());
        $resendThrottleKey = $this->customerOtpResendThrottleKeyFromContext($email, $request->ip());

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please try again.'
            ]);
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'verificationFlow' => $verificationFlow,
            'otpAttemptCount' => (int) session('otp_attempt_count', 0),
            'otpAttemptMax' => self::CUSTOMER_OTP_MAX_ATTEMPTS,
            'otpLockoutSeconds' => RateLimiter::tooManyAttempts($otpThrottleKey, self::CUSTOMER_OTP_MAX_ATTEMPTS)
                ? RateLimiter::availableIn($otpThrottleKey)
                : 0,
            'resendCooldownSeconds' => RateLimiter::tooManyAttempts($resendThrottleKey, self::CUSTOMER_OTP_RESEND_MAX_ATTEMPTS)
                ? RateLimiter::availableIn($resendThrottleKey)
                : 0,
        ]);
    }

    /**
     * Handle OTP verification logic.
     */
    public function verify(Request $request)
    {
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

        $user = User::where('email', trim($request->email))->first();

        if (!$user) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
                $this->throwOtpLockout($otpThrottleKey, 'otp');
            }

            return back()->withErrors(['otp' => 'Account not found.']);
        }

        // 1. Check if OTP matches
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

        // 2. Check if OTP is expired
        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
                $this->throwOtpLockout($otpThrottleKey, 'otp');
            }

            return back()->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        // 3. Mark as verified in Database
        $user->forceFill([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        /**
         * 4. FLOW REDIRECTION LOGIC
         * Dito natin hihiwalayin ang FORGOT PASSWORD flow sa LOGIN/REGISTER flow.
         */

        RateLimiter::clear($otpThrottleKey);

        // --- FLOW: FORGOT PASSWORD ---
        if (session('is_forgot_password') === true || $request->input('verification_flow') === 'forgot_password') {
            $token = session('password_reset_token');
            $emailForReset = $user->email;

            // Markahan na tapos na ang OTP verification para sa security middleware
            $request->session()->put('customer_otp_passed', true);
            
            // Linisin ang temporary flags pero itira ang kailangan para sa reset form
            $request->session()->forget(['is_forgot_password', 'otp_email']);
            
            // IMPORTANT: HUWAG MAG-AUTH::LOGIN DITO. 
            // I-redirect sa Reset Password Section.
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $emailForReset
            ])->with('status', 'OTP Verified! You can now set your new password.');
        }

        // --- FLOW: REGISTER / LOGIN ---
        // Dito lang natin i-lo-log in ang user
        if (!Auth::check()) {
            Auth::login($user);
        }

        $request->session()->regenerate();
        $request->session()->put('customer_otp_passed', true);
        $request->session()->forget([
            'otp_email',
            'password_reset_email',
            'auth_type',
            'otp_passed',
        ]);

        return redirect()->route('dashboard.redirect')->with('status', 'Verified successfully!');
    }

    /**
     * Resend the OTP code.
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

        $email = $request->email ?? session('otp_email') ?? session('password_reset_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Email not found.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) return back()->withErrors(['otp' => 'User not found.']);

        $otp = sprintf("%06d", mt_rand(0, 999999));
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        try {
            $user->notify(new SendOTP($otp));
            RateLimiter::hit($this->customerOtpResendThrottleKey($request), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);
            return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
        } catch (\Exception $e) {
            Log::error("OTP Resend failed: " . $e->getMessage());
            RateLimiter::hit($this->customerOtpResendThrottleKey($request), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);
            return back()->withErrors(['otp' => 'Failed to send code. Please try again later.']);
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
