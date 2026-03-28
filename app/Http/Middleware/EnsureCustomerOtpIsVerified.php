<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerOtpIsVerified
{
    /**
     * Handle an incoming request.
     *
     * Sinisiguro nito na ang mga customers ay dapat makapasa sa OTP verification 
     * bago ma-access ang anumang protected routes ng PRINTIFY & CO.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. AUTH CHECK: Kung hindi naka-login, pabalikin sa login page.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. ROLE BYPASS: Payagan ang admins na dumaan nang walang OTP challenge.
        if ($user->role === 'admin') { 
            return $next($request);
        }

        /**
         * 3. OTP VERIFICATION CHECK
         * Ginagamit natin ang 'customer_otp_passed' flag na sineset natin 
         * sa VerifyOtpController matapos ang matagumpay na input.
         */
        if (session('customer_otp_passed') !== true) {

            // Siguraduhin na ang email ay nasa session para sa display sa OTP view.
            if (!session()->has('otp_email')) {
                session(['otp_email' => $user->email]);
            }

            /**
             * I-redirect sila sa OTP verification page.
             * Nagdagdag tayo ng error message para alam ng user kung bakit sila na-redirect.
             */
            return redirect()->route('customer.otp.verify')
                             ->withErrors([
                                 'otp' => 'Security verification required. Please enter the 6-digit code sent to your email.'
                             ]);
        }

        // 4. ALLOW REQUEST: Kung pasado sa OTP, proceed sa dashboard o profile!
        return $next($request);
    }
}