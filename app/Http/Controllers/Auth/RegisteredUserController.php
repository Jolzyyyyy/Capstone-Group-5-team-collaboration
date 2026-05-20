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
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\OTPVerificationMail; 
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Generate 6-digit OTP (Gamit ang sprintf para laging may leading zeros kung kailangan)
        $otpCode = sprintf("%06d", mt_rand(0, 999999));

        // 3. Create User (Manual Type Registration)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role para sa mga bagong register
            'otp_code' => $otpCode, 
            'otp_expires_at' => Carbon::now()->addMinutes(10),
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
            Mail::to($user->email)->send(new OTPVerificationMail($otpCode));
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
            'auth_type' => 'register'
        ]);

        /**
         * 7. Redirect to Verification Page
         * 🛡️ FIX: Naka-align sa route name na 'customer.otp.verify' sa web.php
         */
        return redirect()->route('customer.otp.verify')
            ->with('status', 'Registration successful! Please check your email for the verification code.');
    }
}