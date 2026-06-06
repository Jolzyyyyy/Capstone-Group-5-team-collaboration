<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        if (!$this->googleConfigIsReady()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login is not configured yet. Please use email and password for now.',
            ]);
        }

        return Socialite::driver('google')
            ->with(['prompt' => 'select_account', 'access_type' => 'offline'])
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        if (!$this->googleConfigIsReady()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google login is not configured yet. Please use email and password for now.',
            ]);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
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

            if ($user) {
                $user->forceFill([
                    'google_id' => $user->google_id ?: $googleId,
                    'google_token' => null,
                ])->save();
            } else {
                [$firstName, $lastName] = $this->splitGoogleName((string) $googleUser->getName());

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'google_id' => $googleId,
                    'google_token' => null,
                    'role' => User::ROLE_CUSTOMER,
                    'password' => Hash::make(Str::random(48)),
                    'has_set_password' => false,
                    'email_verified_at' => null,
                ]);
            }

            Auth::login($user);
            request()->session()->regenerate();
            request()->session()->forget([
                'password_reset_email',
                'password_reset_token',
                'is_forgot_password',
                'customer_otp_passed',
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
                $user->sendOtpNotification($otp);
                RateLimiter::hit(
                    $this->customerOtpResendThrottleKey($user->email, request()->ip()),
                    User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS
                );
            } catch (Throwable $e) {
                Log::error('Google login OTP failed for ' . $user->email . ': ' . $e->getMessage());
            }

            request()->session()->put('otp_email', $user->email);
            request()->session()->put('auth_type', 'account_verification');

            return redirect()->route('otp.verify', [
                'email' => $user->email,
            ])->with('status', 'Google sign-in completed. Enter the verification code sent to your email to finish setup.');

        } catch (Throwable $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }
    }

    /**
     * Ipakita ang setup password view (para sa third-party users).
     */
    public function showSetupPassword()
    {
        return Auth::check() ? view('auth.setup-password') : redirect()->route('login');
    }

    /**
     * I-update ang password ng user.
     */
    public function updateSetupPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('status', 'Password updated successfully!');
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
