<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        // Tumuturo sa resources/views/auth/confirm-password.blade.php na inayos natin kanina
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate password input
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // 2. Validate against current user's email
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // 3. Mark password as confirmed in session (Laravel standard)
        $request->session()->put('auth.password_confirmed_at', time());

        $user = $request->user();

        /**
         * 🛡️ SMART REDIRECTION (PRINTIFY & CO. Logic)
         * Sinisiguro natin na tama ang landing page base sa User Role.
         */
        
        // Admin Flow
        if ($user->canAccessAdminPortal()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Customer/User Flow
        // Kung galing sila sa sensitive action, sinisiguro nating bypass na ang OTP check
        if (!$request->session()->has('otp_passed')) {
            $request->session()->put('otp_passed', true);
        }

        return redirect()->intended(route('dashboard'));
    }
}
