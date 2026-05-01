<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DeveloperAdminClientController extends Controller
{
    public function index(): View
    {
        $pendingAdminClients = User::query()
            ->where('role', User::ROLE_ADMIN_CLIENT)
            ->whereNull('approved_at')
            ->latest()
            ->get();

        $approvedAdminClients = User::query()
            ->whereIn('role', [User::ROLE_ADMIN_CLIENT, User::ROLE_ADMIN])
            ->whereNotNull('approved_at')
            ->latest('approved_at')
            ->get();

        return view('Admin.admin-clients.index', compact('pendingAdminClients', 'approvedAdminClients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_ADMIN_CLIENT,
            'preregistered_by' => $request->user()->id,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Admin client pre-registration created. Approve the account when it is ready for access.');
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isAdminClient(), 404);

        $user->forceFill([
            'role' => User::ROLE_ADMIN_CLIENT,
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ])->save();

        return redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Admin client approved successfully.');
    }

    public function suspend(User $user): RedirectResponse
    {
        abort_unless($user->isAdminClient(), 404);

        $user->forceFill([
            'approved_at' => null,
            'approved_by' => null,
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
        ])->save();

        return redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Admin client access has been suspended.');
    }
}
