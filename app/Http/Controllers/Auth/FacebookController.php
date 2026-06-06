<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::firstOrCreate(
                ['email' => $facebookUser->getEmail()],
                [
                    'name' => $facebookUser->getName() ?: $facebookUser->getNickname() ?: 'Facebook User',
                    'password' => Hash::make(Str::random(32)),
                    'role' => User::ROLE_CUSTOMER,
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);
            request()->session()->regenerate();

            return redirect()->route('dashboard.redirect');
        } catch (Throwable $exception) {
            return redirect()->route('login')->withErrors([
                'email' => 'Facebook authentication failed. Please try again.',
            ]);
        }
    }
}
