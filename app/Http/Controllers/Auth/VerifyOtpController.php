<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Log;

class VerifyOtpController extends Controller
{
    /**
     * Display the OTP verification form.
     */
    public function show(Request $request)
    {
        // Kunin ang email mula sa session flags
        $email = session('otp_email') 
                 ?? session('password_reset_email') 
                 ?? $request->email 
                 ?? (Auth::check() ? Auth::user()->email : null);

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please try again.'
            ]);
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Handle OTP verification logic.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', trim($request->email))->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Account not found.']);
        }

        // 1. Check if OTP matches
        if (trim((string)$user->otp_code) !== trim((string)$request->otp)) {
            return back()->withInput()->withErrors(['otp' => 'The security code you entered is incorrect.']);
        }

        // 2. Check if OTP is expired
        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        // 3. Mark as verified in Database
        $user->forceFill([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        /**
         * 4. FLOW REDIRECTION LOGIC
         * Dito natin hihiwalayin ang FORGOT PASSWORD flow sa LOGIN/REGISTER flow.
         */

        // --- FLOW: FORGOT PASSWORD ---
        if (session('is_forgot_password') === true) {
            $token = session('password_reset_token');
            $emailForReset = $user->email;

            // Markahan na tapos na ang OTP verification para sa security middleware
            $request->session()->put('customer_otp_passed', true);
            
            // Linisin ang temporary flags pero itira ang kailangan para sa reset form
            $request->session()->forget(['is_forgot_password', 'otp_email']);
            
            // IMPORTANT: HUWAG MAG-AUTH::LOGIN DITO. 
            // I-redirect sa Reset Password Section.
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $emailForReset
            ])->with('status', 'OTP Verified! You can now set your new password.');
        }

        // --- FLOW: REGISTER / LOGIN ---
        // Dito lang natin i-lo-log in ang user
        if (!Auth::check()) {
            Auth::login($user);
        }

        $request->session()->regenerate();
        $request->session()->put('customer_otp_passed', true);
        $request->session()->forget(['otp_email', 'password_reset_email']);

        return redirect()->route('dashboard')->with('status', 'Verified successfully!');
    }

    /**
     * Resend the OTP code.
     */
    public function resend(Request $request)
    {
        $email = $request->email ?? session('otp_email') ?? session('password_reset_email');

        if (!$email) {
            return back()->withErrors(['otp' => 'Email not found.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) return back()->withErrors(['otp' => 'User not found.']);

        $otp = sprintf("%06d", mt_rand(0, 999999));
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            $user->notify(new SendOTP($otp));
            return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
        } catch (\Exception $e) {
            Log::error("OTP Resend failed: " . $e->getMessage());
            return back()->withErrors(['otp' => 'Failed to send code. Please try again later.']);
        }
    }
}