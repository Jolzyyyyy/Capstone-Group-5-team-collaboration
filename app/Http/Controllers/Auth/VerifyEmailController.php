<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Notifications\SendOTP;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VerifyEmailController extends Controller
{
    private const CUSTOMER_OTP_MAX_ATTEMPTS = 3;
    private const CUSTOMER_OTP_RESEND_MAX_ATTEMPTS = 1;

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

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (!$user) {
            return redirect()->route('login');
        }

        $otpThrottleKey = $this->customerOtpThrottleKey($request);

        $this->ensureRateLimit(
            $otpThrottleKey,
            self::CUSTOMER_OTP_MAX_ATTEMPTS,
            'otp'
        );

        if (trim((string) $user->otp_code) === trim((string) $request->otp)) {

            // Check expiration
            if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
                $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

                if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
                    $this->throwOtpLockout($otpThrottleKey, 'otp');
                }

                return back()->withErrors([
                    'otp' => 'The code has expired. Please request a new one.',
                ]);
            }

            $wasUnverified = !$user->hasVerifiedEmail();

            // Update DB: mark as verified
            $user->forceFill([
                'email_verified_at' => Carbon::now(),
                'otp_code' => null,
                'otp_expires_at' => null,
            ])->save();

            if ($wasUnverified) {
                event(new Verified($user));
            }

            // Unlock session for dashboard access
            $request->session()->put('customer_otp_passed', true);
            $request->session()->regenerate();
            RateLimiter::clear($otpThrottleKey);

            return redirect()->route('dashboard')->with('status', 'Account verified successfully! Welcome to Printify & Co.');
        }

        $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

        if ($attempts >= self::CUSTOMER_OTP_MAX_ATTEMPTS) {
            $this->throwOtpLockout($otpThrottleKey, 'otp');
        }

        return back()->withErrors([
            'otp' => 'The verification code is incorrect.',
        ]);
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $this->ensureRateLimit(
            $this->customerOtpResendThrottleKey($request),
            self::CUSTOMER_OTP_RESEND_MAX_ATTEMPTS,
            'otp'
        );

        // Generate new OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        // Send OTP email
        try {
            $user->notify(new SendOTP($otp));
            RateLimiter::hit(
                $this->customerOtpResendThrottleKey($request),
                User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS
            );
        } catch (\Exception $e) {
            Log::error("Resend OTP failed: " . $e->getMessage());
            return back()->withErrors([
                'otp_code' => 'Failed to resend OTP. Please try again later.'
            ]);
        }

        return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
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
        return 'customer-otp:' . Str::transliterate(Str::lower((string) $request->user()?->email) . '|' . $request->ip());
    }

    private function customerOtpResendThrottleKey(Request $request): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower((string) $request->user()?->email) . '|' . $request->ip());
    }
}
