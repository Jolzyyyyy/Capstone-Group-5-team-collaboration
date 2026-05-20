<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\OTPVerificationMail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VerifyOtpController extends Controller
{
    /**
     * Ipakita ang OTP verification form.
     * FIXED: Method name is 'show' to match the error requirement.
     */
    public function show(Request $request)
    {
        // 1. Kunin ang email mula sa session o authenticated user
        $email = session('otp_email') 
                 ?? session('password_reset_email') 
                 ?? $request->email 
                 ?? (Auth::check() ? Auth::user()->email : null);

        // 2. Kung walang mahanap na email, ibalik sa login
        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired or invalid request. Please try again.'
            ]);
        }

        // Siguraduhin na ang view file ay resources/views/auth/verify-otp.blade.php
        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Handle ang pag-verify ng OTP code.
     */
    public function verify(Request $request)
    {
        // Validation for the OTP input
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', trim($request->email))->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Account not found.']);
        }

        // 1. Security Check: Tugma ba ang OTP?
        if (trim((string)$user->otp_code) !== trim((string)$request->otp)) {
            return back()->withInput()->withErrors(['otp' => 'The security code you entered is incorrect.']);
        }

        // 2. Security Check: Expired na ba ang code?
        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        // 3. Mark as verified and Clean up OTP fields
        $user->forceFill([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        /**
         * 🛡️ FLOW REDIRECTION
         */

        // --- SCENARIO A: FORGOT PASSWORD FLOW ---
        if (session('is_forgot_password') === true) {
            $token = session('password_reset_token');
            $emailForReset = $user->email;

            $request->session()->put('customer_otp_passed', true);
            $request->session()->forget(['is_forgot_password', 'otp_email']);
            
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $emailForReset
            ])->with('status', 'OTP Verified! Please set your new password below.');
        }

        // --- SCENARIO B: REGISTER / LOGIN FLOW ---
        if (!Auth::check()) {
            Auth::login($user);
        }

        $request->session()->regenerate();
        
        // MAHALAGA: Susi para makalampas sa 'customer_otp' middleware
        $request->session()->put('customer_otp_passed', true);
        
        // Linisin ang otp-related session data
        $request->session()->forget(['otp_email', 'password_reset_email', 'auth_type']);

        // Redirect sa Dashboard
        return redirect()->route('dashboard')->with('status', 'Verified successfully!');
    }

    /**
     * Resend ang OTP code sa user.
     */
    public function resend(Request $request)
    {
        $email = $request->email ?? session('otp_email') ?? session('password_reset_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Email session expired. Please restart the process.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) return back()->withErrors(['otp' => 'User not found.']);

        // Generate bagong 6-digit OTP na may leading zeros
        $otp = sprintf("%06d", mt_rand(0, 999999));
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OTPVerificationMail($otp));
            return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
        } catch (\Exception $e) {
            Log::error("OTP Resend failed for $email: " . $e->getMessage());
            return back()->withErrors(['otp' => 'Failed to send code. Please check your internet connection.']);
        }
    }
}