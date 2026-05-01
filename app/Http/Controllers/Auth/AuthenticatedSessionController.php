<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
        $request->authenticate();

        $user = Auth::user();

        if ($user->canAccessAdminPortal()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Staff and developer accounts must login through the protected portal.',
            ]);
        }

        $request->session()->regenerate();

        if ($user->isCustomer() && !is_null($user->email_verified_at)) {
            $request->session()->put('customer_otp_passed', true);
            $request->session()->forget('otp_email');

            return redirect()->route('dashboard');
        }

        if ($user->isCustomer() && is_null($user->email_verified_at)) {
            $otp = sprintf('%06d', mt_rand(0, 999999));

            $user->forceFill([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
            ])->save();

            try {
                $user->notify(new SendOTP($otp));
                RateLimiter::hit(
                    $this->customerOtpResendThrottleKey($user->email, $request->ip()),
                    User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS
                );
            } catch (\Exception $e) {
                Log::error('Login OTP failed for ' . $user->email . ': ' . $e->getMessage());

                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Unable to send verification code right now. Please try again.',
                ])->onlyInput('email');
            }

            $request->session()->put('otp_email', $user->email);
            $request->session()->forget('customer_otp_passed');

            return redirect()->route('otp.verify', [
                'email' => $user->email,
            ])->with('status', 'A 6-digit verification code has been sent to your email.');
        }

        return redirect()->route('dashboard.redirect');
    }

    /**
     * Destroy an authenticated session (Logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'otp_passed',
            'otp_email',
            'auth_type',
            'customer_otp_passed',
            'password_reset_email',
            'password_reset_token',
            'is_forgot_password',
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function customerOtpResendThrottleKey(string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower($email) . '|' . $ip);
    }
}
