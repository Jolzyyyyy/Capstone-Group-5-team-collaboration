<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 
use App\Mail\OTPVerificationMail;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    /**
     * 1. ADMIN LOGIN SECTION (2-STAGE: Password -> QR)
     */
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isAdmin() && session('2fa_passed')) {
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

        // --- 🛡️ PRE-LOGIN ROLE CHECK ---
        // Hinahanap muna ang user sa database bago mag-attempt ng Auth
        $user = User::where('email', $request->email)->first();

        // Kung walang user o kung hindi siya Admin, reject agad
        if (!$user || !$user->isAdmin()) {
            return back()->withErrors([
                'email' => 'Access denied. This portal is for authorized admins only.',
            ])->onlyInput('email');
        }

        // Kung Admin, i-verify na ang credentials
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            $request->session()->regenerate();

            // INITIAL MARKERS
            session([
                'admin_auth_passed' => true,
                'admin_email' => $user->email,
                'needs_email_otp' => false // FEATURE: Skip Email OTP for Login flow
            ]);

            // Siguraduhing malinis ang verification flags
            session()->forget(['admin_verified', '2fa_passed']);

            // Proceed sa QR Authentication (Phase 2)
            return redirect()->route('admin.security.2fa');
        }

        // Kung Admin pero mali ang password
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * 2. ADMIN REGISTER SECTION (3-STAGE: Password -> Email OTP -> QR)
     */
    public function showRegisterForm()
    {
        return view('Admin.auth.admin-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', 
        ]);

        Auth::login($user);

        session([
            'admin_auth_passed' => true,
            'admin_email' => $user->email,
            'needs_email_otp' => true // FEATURE: Require Email OTP for Registration
        ]);

        session()->forget(['admin_verified', '2fa_passed']);

        // Generate OTP for Registration Verification
        $otp = sprintf("%06d", mt_rand(100000, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        return redirect()->route('admin.otp.verify')
                         ->with('status', 'Admin account created! Verify your email to proceed.');
    }

    /**
     * 3. STAGE 1: EMAIL OTP VERIFICATION (Register Only)
     */
    public function showOtpForm()
    {
        // Security: If login only (no otp needed), redirect to QR
        if (session('needs_email_otp') === false) {
            return redirect()->route('admin.security.2fa');
        }

        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->route('admin.login');
        }

        return view('Admin.auth.admin-otp-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'string']]);

        $user = Auth::user();

        if ($user && $user->otp_code === trim($request->otp)) {
            
            if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
                return back()->withErrors(['otp' => 'Expired na ang code. Mag-resend ng bago.']);
            }

            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = now();
            $user->save();

            session(['admin_verified' => true]);

            return redirect()->route('admin.security.2fa')
                             ->with('status', 'Email verified! Setup your QR Authentication.');
        }

        return back()->withErrors(['otp' => 'Mali ang code. Pakicheck ulit ang email mo.']);
    }

    public function resendOtp()
    {
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new OTPVerificationMail($otp));
            return back()->with('status', 'A new code has been sent to your email.');
        }
        return redirect()->route('admin.login');
    }

    /**
     * 4. LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}