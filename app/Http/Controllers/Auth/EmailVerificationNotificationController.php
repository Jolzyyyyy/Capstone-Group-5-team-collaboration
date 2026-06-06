<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\SendOTP;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmailVerificationNotificationController extends Controller
{
    private const CUSTOMER_OTP_RESEND_MAX_ATTEMPTS = 1;

    /**
     * Send a new email verification notification (OTP).
     * Flow: Customer clicks "Resend" -> Generate New OTP -> Update DB -> Send Email
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Safety check: Siguraduhin na may naka-login na user
        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * 2. Anti-loop Guard
         * Kung ang user ay verified na at nakapasa na sa OTP, 
         * i-redirect na sila sa dashboard para hindi na sila paulit-ulit dito.
         */
        if ($user->hasVerifiedEmail() && $request->session()->get('customer_otp_passed') === true) {
            return redirect()->intended(route('dashboard'));
        }

        $resendThrottleKey = $this->customerOtpResendThrottleKey($user->email, $request->ip());
        $this->ensureRateLimit($resendThrottleKey, self::CUSTOMER_OTP_RESEND_MAX_ATTEMPTS, 'otp');

        // 3. Generate new 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        // I-update ang record sa database kasama ang bagong expiry
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        // 4. Send OTP email using your custom Notification class
        try {
            $user->notify(new SendOTP($otp));
            RateLimiter::hit($resendThrottleKey, User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);
        } catch (\Exception $e) {
            Log::error('Resend OTP failed for user ID ' . $user->id . ': ' . $e->getMessage());

            return back()->withErrors([
                'otp' => 'Failed to send email. Please check your internet connection or try again later.',
            ]);
        }

        /**
         * 5. Return back to the OTP page
         * Gagamit tayo ng 'status' session variable para ipakita 
         * ang success message sa iyong Blade file.
         */
        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    private function ensureRateLimit(string $key, int $maxAttempts, string $bag): void
    {
        if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return;
        }

        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            $bag => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ]);
    }

    private function customerOtpResendThrottleKey(string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower($email) . '|' . $ip);
    }
}
