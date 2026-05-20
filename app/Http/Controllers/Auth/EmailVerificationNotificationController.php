<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification (OTP).
     * Flow: Customer clicks "Resend" -> Generate New OTP -> Update DB -> Send Email
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Safety check: Siguraduhin na may naka-login na user
        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * 2. Anti-loop Guard
         * Kung ang user ay verified na at nakapasa na sa OTP, 
         * i-redirect na sila sa dashboard para hindi na sila paulit-ulit dito.
         */
        if ($user->hasVerifiedEmail() && $request->session()->get('otp_passed')) {
            return redirect()->intended(route('dashboard'));
        }

        // 3. Generate new 6-digit OTP
        $otp = sprintf("%06d", mt_rand(0, 999999));

        // I-update ang record sa database kasama ang bagong expiry
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        // 4. Send OTP email using your custom Notification class
        try {
            $user->notify(new SendOTP($otp));
        } catch (\Exception $e) {
            Log::error('Resend OTP failed for user ID ' . $user->id . ': ' . $e->getMessage());

            return back()->withErrors([
                'otp' => 'Failed to send email. Please check your internet connection or try again later.',
            ]);
        }

        /**
         * 5. Return back to the OTP page
         * Gagamit tayo ng 'status' session variable para ipakita 
         * ang success message sa iyong Blade file.
         */
        return back()->with('status', 'A new verification code has been sent to your email.');
    }
}
