<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $needsPasswordSetup = $request->user()->needsPasswordSetup();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => [$needsPasswordSetup ? 'nullable' : 'required', $needsPasswordSetup ? 'nullable' : 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
            'has_set_password' => true,
        ])->save();

        return back()->with('status', 'password-updated');
    }
}
