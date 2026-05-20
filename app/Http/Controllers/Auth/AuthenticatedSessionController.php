<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\OTPVerificationMail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Ipakita ang Login Form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle ang pag-login ng user.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. I-validate ang Email at Password
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Subukang i-authenticate ang credentials
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        /**
         * 3. ADMIN GUARD
         * Kung admin ang pumasok dito sa customer login, i-logout at ibalik sa login with error.
         * Naka-align ito sa security protocol mo para sa admin portal.
         */
        if (!$user->isCustomer()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Staff accounts must login through the Admin/Developer Portal.',
            ]);
        }

        // 4. Regenerate session para sa security
        $request->session()->regenerate();

        // 5. Generate 6-digit OTP (Formatted to ensure 6 digits)
        $otp = sprintf("%06d", mt_rand(0, 999999));

        // 6. I-save ang OTP sa database ('otp_code' column)
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // 7. I-send ang OTP sa Email ng user
        try {
            Mail::to($user->email)->send(new OTPVerificationMail($otp));
        } catch (\Exception $e) {
            Log::error('Login OTP Email failed for ' . $user->email . ': ' . $e->getMessage());
            // Itutuloy pa rin ang flow para manual resend later kung mag-fail ang initial email
        }

        /**
         * 8. Session Markers
         * Mahalaga: 'customer_otp_passed' ay dapat FALSE para harangin ng middleware 'customer_otp'
         */
        $request->session()->put([
            'customer_otp_passed' => false, 
            'otp_email' => $user->email,
            'auth_type' => 'login',
        ]);

        /**
         * 9. Redirect sa Smart Redirect Logic sa web.php
         * Gagamitin natin ang 'dashboard.redirect' route para malaman kung sa OTP 
         * o sa Dashboard ang tuloy ng user.
         */
        return redirect()->route('dashboard.redirect')
            ->with('status', 'A verification code has been sent to your email.');
    }

    /**
     * Logout ang user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Linisin ang OTP session keys bago mag-logout para fresh start sa susunod
        $request->session()->forget(['customer_otp_passed', 'otp_email', 'auth_type']);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function customerOtpResendThrottleKey(string $email, string $ip): string
    {
        return 'customer-otp-resend:' . Str::transliterate(Str::lower($email) . '|' . $ip);
    }
}

