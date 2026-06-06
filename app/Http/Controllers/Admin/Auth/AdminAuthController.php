<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPVerificationMail;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    private const STAFF_LOGIN_MAX_ATTEMPTS = 5;
    private const STAFF_OTP_MAX_ATTEMPTS = 3;
    private const STAFF_OTP_RESEND_MAX_ATTEMPTS = 1;

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->canAccessAdminPortal() && session('staff_otp_passed')) {
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

        if (($user->isAdminClient() && !$user->isApprovedAdminClient()) || $user->isInvitedAdminPendingApproval()) {
            RateLimiter::hit($this->staffLoginThrottleKey($request));

            return back()->withErrors([
                'email' => 'This admin account is still awaiting developer approval.',
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
            'staff_otp_passed',
        ]);

        session([
            'admin_auth_passed' => true,
            'admin_email' => $user->email,
            'needs_email_otp' => !$user->isDeveloper(),
        ]);

        if ($user->isDeveloper()) {
            $request->session()->put('staff_otp_passed', true);
            $request->session()->forget([
                'admin_auth_passed',
                'needs_email_otp',
                'admin_verified',
                '2fa_passed',
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Developer trusted sign-in complete.');
        }

        return $this->sendStaffOtp($request, $user, 'A verification code has been sent to your email before portal access can continue.');
    }

    public function showRegisterForm()
    {
        if (Auth::check() && Auth::user()->canAccessAdminPortal() && session('staff_otp_passed')) {
            return redirect()->route('admin.dashboard');
        }

        return view('Admin.auth.admin-login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'role' => ['required', 'in:' . User::ROLE_ADMIN . ',' . User::ROLE_DEVELOPER],
            'password' => [
                'required',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $request->session()->forget([
            'admin_verified',
            '2fa_passed',
            'staff_otp_passed',
        ]);

        session([
            'admin_auth_passed' => true,
            'admin_email' => $user->email,
            'needs_email_otp' => !$user->isDeveloper(),
        ]);

        if ($user->isDeveloper()) {
            $request->session()->put('staff_otp_passed', true);
            $request->session()->forget([
                'admin_auth_passed',
                'needs_email_otp',
                'admin_verified',
                '2fa_passed',
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Developer account created. Trusted sign-in complete.');
        }

        return $this->sendStaffOtp($request, $user, 'Admin account created. Please verify your email to continue.');
    }

    public function showOtpForm()
    {
        if (session('staff_otp_passed')) {
            return redirect()->route('admin.dashboard');
        }

        if (!Auth::check() || !Auth::user()->canAccessAdminPortal()) {
            return redirect()->route('admin.login');
        }

        if (Auth::user()->isDeveloper()) {
            request()->session()->put('staff_otp_passed', true);

            return redirect()->route('admin.dashboard');
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
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = Auth::user();

        if (!$user || !$user->canAccessAdminPortal()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unauthorized access.',
            ]);
        }

        if ($user->isDeveloper()) {
            $request->session()->put('staff_otp_passed', true);

            return redirect()->route('admin.dashboard');
        }

        $otpThrottleKey = $this->staffOtpThrottleKey($request);

        $this->ensureRateLimit(
            $otpThrottleKey,
            self::STAFF_OTP_MAX_ATTEMPTS,
            'otp'
        );

        if (trim((string) $user->otp_code) !== trim((string) $request->otp)) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::STAFF_OTP_MAX_ATTEMPTS) {
                $this->throwRateLimitException($otpThrottleKey, 'otp');
            }

            return back()->withErrors([
                'otp' => 'The verification code is incorrect. Please check your email and try again.',
            ]);
        }

        if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
            $attempts = RateLimiter::hit($otpThrottleKey, User::EMAIL_OTP_LOCKOUT_SECONDS);

            if ($attempts >= self::STAFF_OTP_MAX_ATTEMPTS) {
                $this->throwRateLimitException($otpThrottleKey, 'otp');
            }

            return back()->withErrors([
                'otp' => 'This verification code has expired. Please request a new one.',
            ]);
        }

        $user->forceFill([
            'otp_code' => null,
            'otp_expires_at' => null,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $request->session()->regenerate();
        $request->session()->put('staff_otp_passed', true);
        $request->session()->forget([
            'admin_auth_passed',
            'needs_email_otp',
            'admin_verified',
            '2fa_passed',
        ]);
        RateLimiter::clear($otpThrottleKey);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Email verification complete. Welcome to the staff portal.');
    }

    public function resendOtp(Request $request)
    {
        $this->ensureRateLimit(
            $this->staffOtpThrottleKey($request),
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

        if ($user->isDeveloper()) {
            $request->session()->put('staff_otp_passed', true);

            return redirect()->route('admin.dashboard');
        }

        return $this->sendStaffOtp($request, $user, 'A new verification code has been sent to your email.', back());
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_auth_passed',
            'admin_email',
            'needs_email_otp',
            'admin_verified',
            '2fa_passed',
            'staff_otp_passed',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function sendStaffOtp(Request $request, User $user, string $status, $redirect = null)
    {
        $otp = sprintf('%06d', mt_rand(100000, 999999));

        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        try {
            Mail::to($user->email)->send(new OTPVerificationMail($otp));
            RateLimiter::hit($this->staffOtpResendThrottleKey($request), User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS);
        } catch (\Throwable $e) {
            Log::error('Staff OTP send failed for ' . $user->email . ': ' . $e->getMessage());

            if ($redirect) {
                return back()->withErrors([
                    'otp' => 'Unable to resend the verification code right now. Please try again later.',
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unable to send the staff verification code right now. Please try again in a moment.',
            ])->onlyInput('email');
        }

        return ($redirect ?: redirect()->route('admin.otp.verify'))->with('status', $status);
    }

    private function ensureRateLimit(string $key, int $maxAttempts, string $bag): void
    {
        if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return;
        }

        $this->throwRateLimitException($key, $bag);
    }

    private function throwRateLimitException(string $key, string $bag): never
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
