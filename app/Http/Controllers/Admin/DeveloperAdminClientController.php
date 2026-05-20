<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminClientInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DeveloperAdminClientController extends Controller
{
    public function index(): View
    {
        $pendingAdminClients = User::query()
            ->with('adminClientProfile')
            ->withCount(['assignedCustomers', 'managedOrders'])
            ->where(function ($query) {
                $query->where('role', User::ROLE_ADMIN_CLIENT)
                    ->orWhere(function ($adminQuery) {
                        $adminQuery->where('role', User::ROLE_ADMIN)
                            ->whereNotNull('preregistered_by');
                    });
            })
            ->whereNull('approved_at')
            ->latest()
            ->get();

        $approvedAdminClients = User::query()
            ->with('adminClientProfile')
            ->withCount(['assignedCustomers', 'managedOrders'])
            ->where('role', User::ROLE_ADMIN)
            ->whereNotNull('preregistered_by')
            ->whereNotNull('approved_at')
            ->latest('approved_at')
            ->get();

        $recentAuditLogs = AuditLog::query()
            ->with(['actor', 'targetUser'])
            ->latest()
            ->limit(12)
            ->get();

        return view('Admin.admin-clients.index', compact('pendingAdminClients', 'approvedAdminClients', 'recentAuditLogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        ]);

        $plainToken = Str::random(64);
        $inviteUrl = route('admin-client-invitations.show', $plainToken);

        $adminClient = DB::transaction(function () use ($request, $validated, $plainToken): User {
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make(Str::random(64)),
                'role' => User::ROLE_ADMIN,
                'preregistered_by' => $request->user()->id,
                'approved_at' => null,
                'approved_by' => null,
                'invite_token' => hash('sha256', $plainToken),
                'invite_expires_at' => now()->addDays(7),
            ]);

            AuditLog::record(
                'admin_client_preregistered',
                $request->user(),
                $user,
                $user,
                [],
                [
                    'role' => $user->role,
                    'email' => $user->email,
                    'name' => $user->name,
                    'invite_expires_at' => optional($user->invite_expires_at)->toDateTimeString(),
                ],
                $request
            );

            return $user;
        });

        $redirect = redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Admin invitation created. The account can be approved after the admin completes the setup form.')
            ->with('invite_url', $inviteUrl);

        try {
            $adminClient->notify(new AdminClientInvitation($inviteUrl));
        } catch (\Throwable $e) {
            Log::error('Admin client invitation email failed for ' . $adminClient->email . ': ' . $e->getMessage());

            return $redirect->with('warning', 'The invitation was created, but email delivery failed. Use the setup link shown below.');
        }

        return $redirect;
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isAdminClient() || $user->isAdmin(), 404);

        if (!$user->hasAcceptedInvitation() && filled($user->invite_token)) {
            return redirect()
                ->route('developer.admin-clients.index')
                ->withErrors(['approve' => 'This admin account must accept the invitation before approval.']);
        }

        if ($user->hasAcceptedInvitation() && !$user->hasCompletedAdminClientProfile()) {
            return redirect()
                ->route('developer.admin-clients.index')
                ->withErrors(['approve' => 'This admin account must complete the reference profile before approval.']);
        }

        $oldValues = $user->only(['role', 'approved_at', 'approved_by']);

        $user->forceFill([
            'role' => User::ROLE_ADMIN,
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ])->save();

        AuditLog::record(
            'admin_client_approved',
            $request->user(),
            $user,
            $user,
            $oldValues,
            $user->only(['role', 'approved_at', 'approved_by']),
            $request
        );

        return redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Admin account approved successfully.');
    }

    public function suspend(User $user): RedirectResponse
    {
        abort_unless($user->isAdmin() || $user->isAdminClient(), 404);

        $oldValues = $user->only(['approved_at', 'approved_by', 'google2fa_enabled']);

        $user->forceFill([
            'approved_at' => null,
            'approved_by' => null,
            'email_verified_at' => null,
            'otp_code' => null,
            'otp_expires_at' => null,
            'remember_token' => Str::random(60),
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
            'recovery_codes' => null,
        ])->save();

        AuditLog::record(
            'admin_client_suspended',
            request()->user(),
            $user,
            $user,
            $oldValues,
            $user->only(['approved_at', 'approved_by', 'google2fa_enabled']),
            request()
        );

        return redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Admin client access has been suspended.');
    }

    public function assignCustomer(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->isAdmin() || $user->isAdminClient(), 404);
        abort_unless($user->approved_at !== null, 422, 'Customer accounts can only be assigned to approved admins.');

        $validated = $request->validate([
            'customer_email' => ['required', 'email', 'max:255'],
        ]);

        $customer = User::query()
            ->where('role', User::ROLE_CUSTOMER)
            ->where('email', strtolower(trim($validated['customer_email'])))
            ->first();

        if (!$customer) {
            return redirect()
                ->route('developer.admin-clients.index')
                ->withErrors(['customer_email' => 'No customer account was found for that email address.']);
        }

        $oldValues = [
            'admin_client_id' => $customer->admin_client_id,
        ];

        DB::transaction(function () use ($request, $user, $customer, $oldValues) {
            $customer->forceFill([
                'admin_client_id' => $user->id,
            ])->save();

            Order::query()
                ->where('user_id', $customer->id)
                ->update(['admin_client_id' => $user->id]);

            AuditLog::record(
                'customer_assigned_to_admin_client',
                $request->user(),
                $customer,
                $user,
                $oldValues,
                [
                    'admin_client_id' => $user->id,
                    'admin_client_email' => $user->email,
                    'customer_email' => $customer->email,
                ],
                $request
            );
        });

        return redirect()
            ->route('developer.admin-clients.index')
            ->with('success', 'Customer account assigned to admin client successfully.');
    }
}
