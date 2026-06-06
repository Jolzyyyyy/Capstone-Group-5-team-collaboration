<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Notifications\SendOTP;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Ipakita ang registration view (login.blade.php handles both).
     */
    public function create(): View
    {
        return view('auth.login'); // Dahil split-screen ang design mo
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validation - Standard Laravel validation
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $firstName = trim((string) $request->input('first_name'));
        $lastName = trim((string) $request->input('last_name'));
        $fullName = trim((string) $request->input('name'));

        if ($fullName === '') {
            $fullName = trim($firstName.' '.$lastName);
        }

        if ($fullName === '') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
        }

        if ($firstName === '' && $fullName !== '') {
            $parts = preg_split('/\s+/', $fullName, 2);
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? $lastName;
        }

        // 2. Generate 6-digit OTP (Gamit ang sprintf para laging may leading zeros kung kailangan)
        $otpCode = sprintf("%06d", mt_rand(0, 999999));

        // 3. Create User (Manual Type Registration)
        $user = User::create([
            'name' => $fullName,
            'first_name' => $firstName ?: null,
            'last_name' => $lastName ?: null,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role para sa mga bagong register
            'otp_code' => $otpCode, 
            'otp_expires_at' => Carbon::now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        /**
         * 🛡️ FIX: I-comment out ang Registered event. 
         * Ito ang dahilan ng "Route [verification.verify] not defined" error mo
         * dahil pinipilit nito ang default email verification ng Laravel.
         */
        // event(new Registered($user));

        // 4. I-log in ang user para ma-establish ang session
        Auth::login($user);

        // 5. I-send ang OTP Email Notification
        try {
            $user->notify(new SendOTP($otpCode));
            RateLimiter::hit(
                $this->customerOtpResendThrottleKey($user->email, $request->ip()),
                User::EMAIL_OTP_RESEND_COOLDOWN_SECONDS
            );
        } catch (\Exception $e) {
            Log::error("Registration Mail failed for {$user->email}: " . $e->getMessage());
        }

        /**
         * 6. Session Markers (Para sa Middleware at Controller)
         * Ito ang magsasabi sa system na kailangan muna dumaan sa OTP.
         */
        session([
            'customer_otp_passed' => false, // Lock ang dashboard access
            'otp_email' => $user->email,
            'auth_type' => 'account_verification'
        ]);

        /**
         * 7. Redirect to Verification Page
         * 🛡️ FIX: Naka-align sa route name na 'customer.otp.verify' sa web.php
         */
        return redirect()->route('otp.verify', ['email' => $user->email])
            ->with('status', 'Registration successful! Please check your email for the verification code.');
    }

    private function customerOtpResendThrottleKey(string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower($email) . '|' . $ip);
    }
}
