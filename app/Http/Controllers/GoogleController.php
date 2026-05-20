<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\OTPVerificationMail; // In-align ko sa ginagamit nating Mailable
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account consent', 'access_type' => 'offline'])
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update existing user
                $user->update([
                    'google_id'    => $googleUser->id,
                    'google_token' => $googleUser->token,
                ]);
            } else {
                // Create new user (Third-party Registration)
                $user = User::create([
                    'name'              => $googleUser->name,
                    'email'             => $googleUser->email,
                    'google_id'         => $googleUser->id,
                    'google_token'      => $googleUser->token,
                    'role'              => 'customer',
                    'password'          => Hash::make(Str::random(24)), // Temporary password
                    'email_verified_at' => null, // Set to null para dumaan sa OTP flow natin
                ]);
            }

            // 1. GENERATE 6-DIGIT OTP
            $otp = sprintf("%06d", mt_rand(0, 999999));
            
            $user->update([
                'otp_code'       => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // 2. SEND OTP EMAIL
            try {
                Mail::to($user->email)->send(new OTPVerificationMail($otp));
            } catch (Exception $e) {
                Log::error('Google Auth OTP Mail failed: ' . $e->getMessage());
            }

            // 3. LOGIN & SESSION SETUP
            Auth::login($user);
            
            // 4. SESSION MARKERS (Para sa Middleware)
            // Mahalaga: 'customer_otp_passed' ay dapat FALSE para harangin ng middleware
            session([
                'otp_email'           => $user->email,
                'customer_otp_passed' => false, 
                'auth_type'           => 'google',
            ]);

            request()->session()->regenerate();

            // 5. REDIRECT TO VERIFICATION PAGE
            // In-align sa route name na ginagamit natin sa web.php
            return redirect()->route('customer.otp.verify')
                ->with('status', 'Google authentication successful! Please verify your identity with the code sent to your email.');

        } catch (Exception $e) {
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
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('status', 'Password updated successfully!');
    }
}