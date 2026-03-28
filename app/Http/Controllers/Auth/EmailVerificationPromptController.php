<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     * Flow: User logs in -> Middleware checks verification -> This Controller directs them
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();

        // 1. Safety check: Redirect sa login kung walang session
        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * 2. FULL ACCESS CHECK
         * Kung verified na ang email AT nakapasa na sa OTP verification,
         * diretso na sila sa kanilang intended destination (Dashboard).
         */
        if ($user->hasVerifiedEmail() && $request->session()->get('customer_otp_passed') === true) {
            return redirect()->intended(route('dashboard'));
        }

        /**
         * 3. OTP REQUIRED
         * Kung ang user ay verified na ang email pero hindi pa nag-e-enter ng OTP
         * para sa kasalukuyang session, i-redirect sila sa OTP page.
         */
        if ($user->hasVerifiedEmail() && !$request->session()->get('customer_otp_passed')) {
            return redirect()->route('otp.verify'); 
            // Note: Siguraduhin na 'otp.verify' ang name sa iyong web.php
        }

        /**
         * 4. NOT VERIFIED (New User Flow)
         * Kung bago ang user at hindi pa verified ang email, 
         * ipakita ang 'Verify Email' view (resources/views/auth/verify-email.blade.php).
         */
        return view('auth.verify-email');
    }
}