<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPVerificationMail;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    private const STAFF_LOGIN_MAX_ATTEMPTS = 5;
    private const STAFF_OTP_MAX_ATTEMPTS = 5;
    private const STAFF_OTP_RESEND_MAX_ATTEMPTS = 1;

    /**
     * Staff login page.
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->canAccessAdminPortal() && session('2fa_passed')) {
            return redirect()->route('admin.dashboard');
        }

        return view('Admin.auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $this->ensureRateLimit(
            $this->staffLoginThrottleKey($request),
            self::STAFF_LOGIN_MAX_ATTEMPTS,
            'email'
        );

        $user = User::where('email', trim((string) $request->email))->first();

        if (!$user || !$user->canAccessAdminPortal()) {
            RateLimiter::hit($this->staffLoginThrottleKey($request));

            return back()->withErrors([
                'email' => 'Access denied. This portal is for approved staff and developers only.',
            ])->onlyInput('email');
        }

        if ($user->isAdminClient() && !$user->isApprovedAdminClient()) {
            RateLimiter::hit($this->staffLoginThrottleKey($request));

            return back()->withErrors([
                'email' => 'This staff account is still awaiting developer approval.',
            ])->onlyInput('email');
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($this->staffLoginThrottleKey($request));

            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        RateLimiter::clear($this->staffLoginThrottleKey($request));

        $request->session()->forget([
            'admin_auth_passed',
            'admin_email',
            'needs_email_otp',
            'admin_verified',
            '2fa_passed',
        ]);

        $needsEmailOtp = is_null($user->email_verified_at);

        session([
            'admin_auth_passed' => true,
            'admin_email' => $user->email,
            'needs_email_otp' => $needsEmailOtp,
        ]);

        session()->forget(['admin_verified', '2fa_passed']);

        if ($needsEmailOtp) {
            $otp = sprintf('%06d', mt_rand(100000, 999999));
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES);
            $user->save();

            try {
                Mail::to($user->email)->send(new OTPVerificationMail($otp));
                RateLimiter::hit($this->staffOtpResendThrottleKeyFromContext($user->email, $request->ip()), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);
            } catch (\Throwable $e) {
                Log::error('Staff OTP send failed for ' . $user->email . ': ' . $e->getMessage());

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Unable to send the staff verification code right now. Please try again in a moment.',
                ])->onlyInput('email');
            }

            return redirect()->route('admin.otp.verify')
                ->with('status', 'A verification code has been sent to your email before portal access can continue.');
        }

        return redirect()->route('admin.security.2fa');
    }

    /**
     * Public admin registration is disabled. Staff accounts are created by developers.
     */
    public function showRegisterForm()
    {
        abort(404);
    }

    public function register(Request $request)
    {
        abort(404);
    }

    /**
     * Staff email OTP screen.
     */
    public function showOtpForm()
    {
        if (session('needs_email_otp') === false) {
            return redirect()->route('admin.security.2fa');
        }

        if (!Auth::check() || !Auth::user()->canAccessAdminPortal()) {
            return redirect()->route('admin.login');
        }

        $email = (string) optional(Auth::user())->email;
        $otpThrottleKey = $this->staffOtpThrottleKeyFromContext($email, request()->ip());
        $resendThrottleKey = $this->staffOtpResendThrottleKeyFromContext($email, request()->ip());

        return view('Admin.auth.admin-otp-verify', [
            'otpLockoutSeconds' => RateLimiter::tooManyAttempts($otpThrottleKey, self::STAFF_OTP_MAX_ATTEMPTS)
                ? RateLimiter::availableIn($otpThrottleKey)
                : 0,
            'resendCooldownSeconds' => RateLimiter::tooManyAttempts($resendThrottleKey, self::STAFF_OTP_RESEND_MAX_ATTEMPTS)
                ? RateLimiter::availableIn($resendThrottleKey)
                : 0,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string'],
        ]);

        $otpThrottleKey = $this->staffOtpThrottleKey($request);

        $this->ensureRateLimit(
            $otpThrottleKey,
            self::STAFF_OTP_MAX_ATTEMPTS,
            'otp'
        );

        $user = Auth::user();

        if (!$user || trim((string) $user->otp_code) !== trim((string) $request->otp)) {
            RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            return back()->withErrors([
                'otp' => 'The verification code is incorrect. Please check your email and try again.',
            ]);
        }

        if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
            RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            return back()->withErrors([
                'otp' => 'This verification code has expired. Please request a new one.',
            ]);
        }

        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->email_verified_at = now();
        $user->save();

        session(['admin_verified' => true]);
        $request->session()->regenerate();
        RateLimiter::clear($otpThrottleKey);

        return redirect()->route('admin.security.2fa')
            ->with('status', 'Email verified. Continue with your authenticator setup.');
    }

    public function resendOtp(Request $request)
    {
        $otpThrottleKey = $this->staffOtpThrottleKey($request);

        $this->ensureRateLimit(
            $otpThrottleKey,
            self::STAFF_OTP_MAX_ATTEMPTS,
            'otp'
        );

        $this->ensureRateLimit(
            $this->staffOtpResendThrottleKey($request),
            self::STAFF_OTP_RESEND_MAX_ATTEMPTS,
            'otp'
        );

        $user = Auth::user();

        if (!$user || !$user->canAccessAdminPortal()) {
            return redirect()->route('admin.login');
        }

        $otp = sprintf('%06d', mt_rand(100000, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES);
        $user->save();

        try {
            Mail::to($user->email)->send(new OTPVerificationMail($otp));
        } catch (\Throwable $e) {
            Log::error('Staff OTP resend failed for ' . $user->email . ': ' . $e->getMessage());
            RateLimiter::hit($this->staffOtpResendThrottleKey($request), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);

            return back()->withErrors([
                'otp' => 'Unable to resend the verification code right now. Please try again later.',
            ]);
        }

        RateLimiter::hit($this->staffOtpResendThrottleKey($request), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    /**
     * Staff logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_auth_passed',
            'admin_email',
            'needs_email_otp',
            'admin_verified',
            '2fa_passed',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
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

    private function staffLoginThrottleKey(Request $request): string
    {
        return 'staff-login:' . Str::transliterate(Str::lower($request->input('email', '')) . '|' . $request->ip());
    }

    private function staffOtpThrottleKey(Request $request): string
    {
        return $this->staffOtpThrottleKeyFromContext((string) optional(Auth::user())->email, $request->ip());
    }

    private function staffOtpResendThrottleKey(Request $request): string
    {
        return $this->staffOtpResendThrottleKeyFromContext((string) optional(Auth::user())->email, $request->ip());
    }

    private function staffOtpThrottleKeyFromContext(?string $email, string $ip): string
    {
        return 'staff-otp:' . Str::transliterate(Str::lower((string) $email) . '|' . $ip);
    }

    private function staffOtpResendThrottleKeyFromContext(?string $email, string $ip): string
    {
        return 'staff-otp-resend:' . Str::transliterate(Str::lower((string) $email) . '|' . $ip);
    }
}
