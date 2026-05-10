<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (!$this->googleConfigIsReady()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login is not configured yet. Please use email and password for now.',
            ]);
        }

        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account',
            ])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        if (!$this->googleConfigIsReady()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login is not configured yet. Please use email and password for now.',
            ]);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::warning('Google login callback failed: ' . $e->getMessage());

            return redirect()->route('login')->withErrors([
                'email' => 'Google login could not be completed. Please try again or use email and password.',
            ]);
        }

        $googleId = (string) $googleUser->getId();
        $email = Str::lower(trim((string) $googleUser->getEmail()));

        if ($googleId === '' || $email === '') {
            return redirect()->route('login')->withErrors([
                'email' => 'Google did not return the account details needed for login.',
            ]);
        }

        $user = User::query()
            ->where('email', $email)
            ->orWhere('google_id', $googleId)
            ->first();

        if ($user && $user->canAccessAdminPortal()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Staff and developer accounts must login through the protected portal.',
            ]);
        }

        $createdNewUser = false;

        if (!$user) {
            [$firstName, $lastName] = $this->splitGoogleName((string) $googleUser->getName());

            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make(Str::random(48)),
                'has_set_password' => false,
                'role' => User::ROLE_CUSTOMER,
                'google_id' => $googleId,
                'google_token' => null,
                'email_verified_at' => null,
            ]);

            $createdNewUser = true;
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleId,
                'google_token' => null,
            ])->save();
        }

        Auth::login($user);
        request()->session()->regenerate();
        request()->session()->forget([
            'password_reset_email',
            'password_reset_token',
            'is_forgot_password',
            'otp_passed',
        ]);

        if (!is_null($user->email_verified_at)) {
            request()->session()->put('customer_otp_passed', true);
            request()->session()->forget(['otp_email', 'auth_type']);

            return redirect()->route('dashboard')
                ->with('status', 'Signed in with Google.');
        }

        $otp = sprintf('%06d', mt_rand(0, 999999));
        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        try {
            $user->notify(new SendOTP($otp));
            RateLimiter::hit(
                $this->customerOtpResendThrottleKey($user->email, request()->ip()),
                User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS
            );
        } catch (Throwable $e) {
            Log::error('Google login OTP failed for ' . $user->email . ': ' . $e->getMessage());

            Auth::guard('web')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            if ($createdNewUser) {
                $user->delete();
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Unable to send your verification code right now. Please try again.',
            ]);
        }

        request()->session()->put('otp_email', $user->email);
        request()->session()->put('auth_type', 'account_verification');
        request()->session()->forget('customer_otp_passed');

        return redirect()->route('otp.verify', [
            'email' => $user->email,
        ])->with('status', 'Google sign-in completed. Enter the verification code sent to your email to finish setup.');
    }

    private function googleConfigIsReady(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    private function splitGoogleName(string $name): array
    {
        $name = trim($name);

        if ($name === '') {
            return ['Google', 'Customer'];
        }

        $parts = preg_split('/\s+/', $name, 2);

        return [
            $parts[0] ?? 'Google',
            $parts[1] ?? 'Customer',
        ];
    }

    private function customerOtpResendThrottleKey(string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower($email) . '|' . $ip);
    }
}
