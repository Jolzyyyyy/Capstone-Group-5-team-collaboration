<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminClientInvitationController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $user = $this->findInvitedAdminClient($token);

        if (!$user) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This admin invitation is invalid, expired, or already accepted.',
            ]);
        }

        return view('Admin.admin-clients.accept-invite', [
            'token' => $token,
            'adminClient' => $user,
        ]);
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $user = $this->findInvitedAdminClient($token);

        if (!$user) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'This admin invitation is invalid, expired, or already accepted.',
            ]);
        }

        $validated = $request->validate([
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'business_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:50'],
            'business_address' => ['required', 'string', 'max:2000'],
            'reference_notes' => ['nullable', 'string', 'max:4000'],
        ]);

        DB::transaction(function () use ($request, $user, $validated): void {
            $user->forceFill([
                'password' => Hash::make($validated['password']),
                'email_verified_at' => null,
                'invite_token' => null,
                'invite_expires_at' => null,
                'invitation_accepted_at' => now(),
                'otp_code' => null,
                'otp_expires_at' => null,
            ])->save();

            $profile = $user->adminClientProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => trim($validated['business_name']),
                    'contact_person' => trim($validated['contact_person']),
                    'contact_number' => trim($validated['contact_number']),
                    'business_address' => trim($validated['business_address']),
                    'reference_notes' => trim((string) ($validated['reference_notes'] ?? '')) ?: null,
                    'profile_completed_at' => now(),
                ]
            );

            AuditLog::record(
                'admin_client_invite_accepted',
                null,
                $user,
                $profile,
                [],
                [
                    'email' => $user->email,
                    'business_name' => $profile->business_name,
                    'contact_person' => $profile->contact_person,
                ],
                $request
            );
        });

        return redirect()
            ->route('admin.login')
            ->with('status', 'Reference profile saved. Please wait for developer approval before logging in.');
    }

    private function findInvitedAdminClient(string $token): ?User
    {
        return User::query()
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_ADMIN_CLIENT])
            ->where('invite_token', hash('sha256', $token))
            ->whereNull('invitation_accepted_at')
            ->where(function ($query) {
                $query->whereNull('invite_expires_at')
                    ->orWhere('invite_expires_at', '>', now());
            })
            ->first();
    }
}
