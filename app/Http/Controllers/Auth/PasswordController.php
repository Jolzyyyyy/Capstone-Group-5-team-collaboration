<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     * Gumagana ito para sa Guest (Reset) at Auth (Profile Change).
     */
    public function update(Request $request): RedirectResponse
    {
        /**
         * 1. DYNAMIC VALIDATION
         * Kapag Guest (Reset Password), hindi natin hihingiin ang 'current_password'.
         */
        $rules = [
            'password' => ['required', Password::defaults(), 'confirmed'],
            'action_type' => ['required', 'string'],
        ];

        // Hihingi lang ng current_password kung ang user ay naka-login na (Profile Update)
        if (Auth::check()) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $validated = $request->validate($rules);

        /**
         * 2. IDENTIFY USER
         * Kunin ang user base sa session o base sa email na nasa form.
         */
        $user = Auth::check() 
            ? $request->user() 
            : User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User account not found.']);
        }

        /**
         * 3. UPDATE DATA
         */
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Panatilihing 'passed' ang OTP status
        if ($user->role === 'customer') {
            $request->session()->put('customer_otp_passed', true);
        }

        /**
         * 4. SMART REDIRECTION (FIXED)
         * Imbes na return back(), gagamit tayo ng specific routes para lumipat ang page.
         */
        
        // Choice A: Auto Login / Dashboard
        if ($request->action_type === 'auto_login') {
            Auth::login($user); 
            
            // Gamitin ang redirector route mo sa web.php
            return redirect()->route('dashboard.redirect');
        }

        // Choice B: Manual Login / Logout first
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Password updated! Please login.');
    }
}