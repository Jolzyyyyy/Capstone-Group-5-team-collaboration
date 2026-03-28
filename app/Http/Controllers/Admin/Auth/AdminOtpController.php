<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\OTPVerificationMail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminOtpController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN EMAIL OTP METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Ipakita ang OTP Verification page para sa Admin.
     */
    public function showAdminOtp(): View|RedirectResponse
    {
        // --- 🛡️ SECURITY SHIELD ---
        // 1. Dapat logged in.
        // 2. Dapat ang role ay 'admin'. Kapag customer, BLOCK at pabalikin sa login.
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Unauthorized access. Admins only.']);
        }

        // Kung verified na ang Email OTP ni Admin, huwag na pabalikin dito.
        // Itapon na siya sa susunod na step: QR Authentication (Google 2FA).
        if (session('admin_verified')) {
            return redirect()->route('admin.2fa.setup'); 
        }

        return view('Admin.auth.admin-otp-verify', ['email' => Auth::user()->email]);
    }

    /**
     * I-verify ang Email OTP para sa Admin.
     */
    public function verifyAdminOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string'],
        ]);

        $user = Auth::user();

        // Double check if admin (Safety first)
        if (!$user || !$user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Invalid session.']);
        }

        // Check if OTP matches
        if ($user->otp_code === trim($request->otp)) {
            
            // Check expiry gamit ang helper sa Model
            if ($user->isOtpExpired()) {
                return back()->withErrors(['otp' => 'The admin verification code has expired.']);
            }

            // Mark session as Email Verified (Phase 1 Complete)
            session(['admin_verified' => true]);

            // Linisin ang OTP columns para sa security
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // REDIRECT: Papunta sa susunod na requirement mo (QR AUTHENTICATION)
            return redirect()->route('admin.2fa.setup')->with('status', 'Email verified! Now, setup your QR Authentication.');
        }

        return back()->withErrors(['otp' => 'Incorrect code. Please check your admin email.']);
    }

    /**
     * Resend OTP Logic (Admin Only)
     */
    public function resendAdminOtp(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            return redirect()->route('admin.login');
        }

        // Generate new 6-digit OTP
        $otp = sprintf("%06d", mt_rand(100000, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send Email specifically for Admin
        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        return back()->with('status', 'A new verification code has been sent to your admin email.');
    }
}