<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminClientProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        abort_unless($user->isAdminClient(), 403);

        return view('Admin.admin-clients.profile', [
            'profile' => $user->adminClientProfile()->firstOrNew([
                'user_id' => $user->id,
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isAdminClient(), 403);

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:50'],
            'business_address' => ['required', 'string', 'max:2000'],
            'reference_notes' => ['nullable', 'string', 'max:4000'],
        ]);

        $profile = $user->adminClientProfile()->firstOrNew([
            'user_id' => $user->id,
        ]);

        $oldValues = $profile->exists
            ? $profile->only(['business_name', 'contact_person', 'contact_number', 'business_address', 'reference_notes'])
            : [];

        $profile->fill([
            'business_name' => trim($validated['business_name']),
            'contact_person' => trim($validated['contact_person']),
            'contact_number' => trim($validated['contact_number']),
            'business_address' => trim($validated['business_address']),
            'reference_notes' => trim((string) ($validated['reference_notes'] ?? '')) ?: null,
            'profile_completed_at' => $profile->profile_completed_at ?? now(),
        ]);

        $profile->save();

        AuditLog::record(
            'admin_client_profile_updated',
            $user,
            $user,
            $profile,
            $oldValues,
            $profile->only(['business_name', 'contact_person', 'contact_number', 'business_address', 'reference_notes']),
            $request
        );

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Reference profile updated successfully.');
    }
}
