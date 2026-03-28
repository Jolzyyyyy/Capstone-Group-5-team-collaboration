<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class SecurityController extends Controller
{
    /**
     * Ipakita ang 2FA Setup/Verification Form.
     */
    public function show2faForm(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        // --- 1. SECURITY GUARD: Role & Stage Check ---
        // Dapat Admin lang ang nandito.
        if (!$user || !$user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Unauthorized access.']);
        }

        // Dapat verified na ang Email OTP (Phase 1) bago makita ang QR (Phase 2).
        // Ginagamit natin ang 'admin_verified' marker na galing sa AdminAuthController@verifyOtp.
        if (!$request->session()->has('admin_verified') && is_null($user->email_verified_at)) {
            return redirect()->route('admin.otp.verify')
                             ->withErrors(['otp' => 'Please verify your email OTP first.']);
        }

        // --- 2. SUCCESS CHECK ---
        // Kung tapos na ang 2FA (Phase 2), huwag na pabalikin dito, diretso dashboard.
        if ($request->session()->has('2fa_passed')) {
            return redirect()->route('admin.dashboard');
        }

        // --- 3. SECRET KEY MANAGEMENT ---
        $currentSecret = $user->google2fa_secret;

        // Setup Phase: Mag-generate lang kung TALAGANG wala pang secret sa DB.
        if (empty($currentSecret)) {
            $currentSecret = $google2fa->generateSecretKey();
            
            // I-save agad ang secret key para hindi mag-iba sa bawat refresh.
            $user->google2fa_secret = $currentSecret; 
            $user->google2fa_enabled = false; 
            $user->save();
        }

        // --- 4. GENERATE QR CODE URL ---
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            'PRINTIFY & CO. (ADMIN)', 
            $user->email,
            $currentSecret
        );

        $renderUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrCodeUrl);

        // I-render ang view
        return view('Admin.security.setup', [
            'qrCodeUrl' => $renderUrl,
            'secretKey' => $currentSecret,
            'user' => $user,
            'isSetup' => !$user->google2fa_enabled 
        ]);
    }

    /**
     * I-verify ang 6-digit code mula sa Google Authenticator app.
     */
    public function activate2fa(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|string|min:6|max:8',
        ]);

        $user = Auth::user();
        $google2fa = new Google2FA();
        
        // Clean input
        $userInput = str_replace([' ', '-'], '', $request->one_time_password);
        $secret = $user->google2fa_secret;

        if (empty($secret)) {
            return back()->withErrors(['one_time_password' => 'Security secret is missing. Refresh the page.']);
        }

        /**
         * VERIFICATION:
         * Window 4 = Approx 2-minute allowance para sa clock sync issues.
         */
        $valid = $google2fa->verifyKey($secret, $userInput, 4); 

        if ($valid) {
            // A. Mark as fully enabled sa Database.
            $user->google2fa_enabled = true;
            $user->save();

            // B. FINAL STAGE SUCCESS MARKER: 
            // Ito ang gagamitin natin sa Middleware para payagan ang Dashboard access.
            $request->session()->put('2fa_passed', true);
            
            // C. CLEAN UP: Panatilihin ang mga kailangan lang.
            $request->session()->forget(['admin_auth_passed', 'admin_verified']);
            
            // D. PERSIST SESSION
            $request->session()->save(); 

            return redirect()->route('admin.dashboard')
                             ->with('success', 'Security Verified. Welcome to Admin Panel.');
        }

        return back()->withErrors(['one_time_password' => 'Invalid or expired QR code. Please check your authenticator app.']);
    }
}