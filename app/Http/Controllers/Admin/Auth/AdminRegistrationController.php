<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OTPVerificationMail; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password; // Gagamitin natin ito para sa strict validation
use Illuminate\View\View;

class AdminRegistrationController extends Controller
{
    /**
     * Ipakita ang Admin Register View.
     */
    public function create(): View
    {
        // Dapat ang file ay nasa: resources/views/Admin/auth/admin-register.blade.php
        return view('Admin.auth.admin-register');
    }

    /**
     * Handle Admin registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // FIXED: Strict Password Validation (8+ chars, Letters, Numbers, Symbols)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)     // Minimum 8 characters
                    ->letters()      // Dapat may letters
                    ->mixedCase()    // Dapat may Uppercase at Lowercase
                    ->numbers()      // Dapat may numbers
                    ->symbols()      // Dapat may special symbols (@$!%*#?&)
            ],
        ]);

        // 1. Create Admin User (Hardcoded 'admin' role para safe)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', 
        ]);

        // 2. Generate OTP Code (6 Digits)
        $otp = sprintf("%06d", mt_rand(100000, 999999));
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES);
        $user->save();

        // 3. Ipadala ang Email (Tiyaking gumagana ang OTPVerificationMail class mo)
        Mail::to($user->email)->send(new OTPVerificationMail($otp));

        // 4. Session Security - I-lock ang session sa Admin Level
        Auth::login($user);
        
        session([
            'admin_auth_passed' => true,
            'admin_email' => $user->email,
            'user_role' => 'admin', // Marker na admin ang pumasok
        ]);

        // Siguraduhing malinis ang verification flags bago mag-OTP
        session()->forget('admin_verified');
        session()->forget('2fa_passed');

        event(new Registered($user));

        // 5. REDIRECT sa Admin-specific OTP route
        // Ito ay dapat mag-match sa route name sa web.php mo
        return redirect()->route('admin.otp.verify')
                         ->with('status', 'Admin account created! Please check your email for the verification code.');
    }
}
