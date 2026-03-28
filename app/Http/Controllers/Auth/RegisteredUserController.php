<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Strict Validation: Letters, Mixed Case, Numbers, at Symbols para sa security.
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        // 2. Generate initial 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        // 3. Create user record with default 'customer' role
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role' => 'customer', 
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // 4. Send the OTP Notification immediately
        try {
            /** * IMPORTANT: Kung hindi nag-send ang email, check your .env 
             * at siguraduhin na ang SendOTP notification ay walang 'implements ShouldQueue' 
             * kung wala kang running queue worker.
             */
            $user->notify(new SendOTP($otp));
            
        } catch (\Exception $e) {
            Log::error('Registration OTP failed for ' . $user->email . ': ' . $e->getMessage());

            // Delete the user record para hindi magka-duplicate email error sa next try
            $user->delete();

            return back()->withErrors([
                'email' => 'Unable to send verification email. Please check your internet or mail settings.',
            ])->withInput();
        }

        // 5. Auto-login the user into a "restricted" session
        Auth::login($user);

        // ✅ After registration go to homepage
        return redirect('/');
    }
}