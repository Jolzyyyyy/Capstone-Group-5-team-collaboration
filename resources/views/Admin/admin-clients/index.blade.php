<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6 space-y-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">Developer Access</p>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Manage Admin Clients</h1>
            <p class="mt-2 text-sm text-slate-600">Invite admins, review their reference profile, approve access, and keep an audit trail.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Pending Approval</p>
                <p class="mt-3 text-3xl font-black text-slate-900">{{ $pendingAdminClients->count() }}</p>
                <p class="mt-1 text-sm text-slate-500">Waiting for developer action</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Approved Admins</p>
                <p class="mt-3 text-3xl font-black text-slate-900">{{ $approvedAdminClients->count() }}</p>
                <p class="mt-1 text-sm text-slate-500">Can enter the Admin Dashboard</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">Access Boundary</p>
                <p class="mt-3 text-lg font-black text-slate-900">Developer approves admins</p>
                <p class="mt-1 text-sm text-slate-500">Admin accounts stay separate from customers</p>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('invite_url'))
            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                <p class="font-bold">Latest invite setup link</p>
                <a href="{{ session('invite_url') }}" class="mt-1 block break-all underline">{{ session('invite_url') }}</a>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[minmax(0,0.9fr),minmax(0,1.35fr)]">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black text-slate-900">Invite Admin</h2>
                <p class="mt-1 text-sm text-slate-500">The admin sets their own password and reference profile before developer approval.</p>

                <form method="POST" action="{{ route('developer.admin-clients.store') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Work Email')" />
                        <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <x-primary-button>
                        {{ __('Send Invite') }}
                    </x-primary-button>
                </form>
            </section>

            <section class="space-y-6">
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-700">Approval Queue</p>
                            <h2 class="text-lg font-black text-slate-900">Pending Approval</h2>
                        </div>
                        <p class="text-xs font-bold text-amber-700">Use the Approve button to unlock Admin Dashboard access.</p>
                    </div>
                    <div class="mt-4 space-y-4">
                        @forelse ($pendingAdminClients as $pendingUser)
                            @php
                                $profile = $pendingUser->adminClientProfile;
                                $isLegacyPending = !$pendingUser->hasAcceptedInvitation() && blank($pendingUser->invite_token);
                                $readyForApproval = $isLegacyPending || ($pendingUser->hasAcceptedInvitation() && $pendingUser->hasCompletedAdminClientProfile());
                            @endphp
                            <div class="rounded-xl border border-amber-200 bg-white p-4 shadow-sm">
                                <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr),auto] xl:items-start">
                                    <div class="min-w-0 space-y-2">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $pendingUser->name }}</p>
                                            <p class="text-sm text-slate-600">{{ $pendingUser->email }}</p>
                                        </div>
                                        <p class="inline-flex rounded-lg px-3 py-1 text-xs font-black uppercase tracking-[0.16em] {{ $readyForApproval ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $readyForApproval ? ($isLegacyPending ? 'Legacy account - profile required after login' : 'Ready for approval') : ($pendingUser->hasAcceptedInvitation() ? 'Profile incomplete' : 'Waiting for invite acceptance') }}
                                        </p>
                                        @if ($profile)
                                            <div class="rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
                                                <p><span class="font-bold">Business:</span> {{ $profile->business_name }}</p>
                                                <p><span class="font-bold">Contact:</span> {{ $profile->contact_person }} / {{ $profile->contact_number }}</p>
                                                <p><span class="font-bold">Address:</span> {{ $profile->business_address }}</p>
                                                @if ($profile->reference_notes)
                                                    <p><span class="font-bold">Notes:</span> {{ $profile->reference_notes }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-col gap-2 xl:min-w-[180px]">
                                        <form method="POST" action="{{ route('developer.admin-clients.approve', $pendingUser) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" @disabled(!$readyForApproval) class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-black uppercase tracking-[0.12em] text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-slate-300">
                                                Approve
                                            </button>
                                        </form>
                                        @unless($readyForApproval)
                                            <p class="text-xs font-semibold leading-5 text-slate-500">The admin must finish the invite setup before this button unlocks.</p>
                                        @endunless
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No pending admin accounts.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900">Approved Admin Accounts</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($approvedAdminClients as $approvedUser)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $approvedUser->name }}</p>
                                        <p class="text-sm text-slate-600">{{ $approvedUser->email }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">
                                            Approved {{ optional($approvedUser->approved_at)->format('M d, Y h:i A') }}
                                        </p>
                                        @if ($approvedUser->adminClientProfile)
                                            <p class="mt-2 text-xs text-slate-500">{{ $approvedUser->adminClientProfile->business_name }}</p>
                                        @endif
                                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-bold text-slate-600">
                                            <span class="rounded-lg bg-white px-3 py-2">{{ $approvedUser->assigned_customers_count }} assigned customers</span>
                                            <span class="rounded-lg bg-white px-3 py-2">{{ $approvedUser->managed_orders_count }} assigned orders</span>
                                        </div>
                                        <form method="POST" action="{{ route('developer.admin-clients.assign-customer', $approvedUser) }}" class="mt-3 flex flex-col gap-2 sm:flex-row">
                                            @csrf
                                            @method('PATCH')
                                            <input
                                                type="email"
                                                name="customer_email"
                                                placeholder="customer@example.com"
                                                class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm shadow-sm focus:border-orange-400 focus:ring-orange-400"
                                                required
                                            >
                                            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-700">
                                                Assign Customer
                                            </button>
                                        </form>
                                    </div>
                                    <form method="POST" action="{{ route('developer.admin-clients.suspend', $approvedUser) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg border border-rose-200 bg-white px-4 py-2 text-sm font-black uppercase tracking-[0.12em] text-rose-600 transition hover:bg-rose-50">
                                            Suspend
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No approved admin accounts yet.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-black text-slate-900">Recent Audit Activity</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($recentAuditLogs as $log)
                    <div class="py-3 text-sm text-slate-600">
                        <p class="font-bold text-slate-900">{{ str_replace('_', ' ', ucfirst($log->action)) }}</p>
                        <p>
                            Actor: {{ optional($log->actor)->email ?? 'System / invite link' }}
                            @if ($log->targetUser)
                                | Target: {{ $log->targetUser->email }}
                            @endif
                            | {{ $log->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No audit activity yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
