<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    /**
     * Authenticator-app 2FA has been replaced by email OTP per staff login.
     * Keep these endpoints as redirects so old links do not break.
     */
    public function show2faForm(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || !$user->canAccessAdminPortal()) {
            Auth::logout();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Unauthorized access.',
            ]);
        }

        if (!$request->session()->has('staff_otp_passed')) {
            return redirect()->route('admin.otp.verify');
        }

        return redirect()->route('admin.dashboard');
    }

    public function activate2fa(Request $request): RedirectResponse
    {
        return $this->show2faForm($request);
    }

    public function verify2fa(Request $request): RedirectResponse
    {
        return $this->activate2fa($request);
    }
}
