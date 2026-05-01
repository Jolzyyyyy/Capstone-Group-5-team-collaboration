<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6 space-y-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">Developer Access</p>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Admin Client Pre-registration</h1>
            <p class="mt-2 text-sm text-slate-600">Create staff accounts here, then approve them before they can access the protected portal.</p>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr),minmax(0,1.25fr)]">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-black text-slate-900">Create Admin Client</h2>
                <p class="mt-1 text-sm text-slate-500">This account will stay blocked until a developer approves it.</p>

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

                    <div>
                        <x-input-label for="password" :value="__('Temporary Password')" />
                        <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Temporary Password')" />
                        <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <x-primary-button>
                        {{ __('Create Pre-registration') }}
                    </x-primary-button>
                </form>
            </section>

            <section class="space-y-6">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900">Pending Approval</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($pendingAdminClients as $pendingUser)
                            <div class="rounded-xl border border-amber-200 bg-white px-4 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $pendingUser->name }}</p>
                                        <p class="text-sm text-slate-600">{{ $pendingUser->email }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-amber-700">Waiting for developer approval</p>
                                    </div>
                                    <form method="POST" action="{{ route('developer.admin-clients.approve', $pendingUser) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-bold text-white transition hover:bg-slate-700">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No pending admin client accounts.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900">Approved Admin Clients</h2>
                    <div class="mt-4 space-y-4">
                        @forelse ($approvedAdminClients as $approvedUser)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $approvedUser->name }}</p>
                                        <p class="text-sm text-slate-600">{{ $approvedUser->email }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">
                                            Approved {{ optional($approvedUser->approved_at)->format('M d, Y h:i A') ?? 'legacy account' }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('developer.admin-clients.suspend', $approvedUser) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg border border-rose-200 bg-white px-4 py-2 text-sm font-bold text-rose-600 transition hover:bg-rose-50">
                                            Suspend
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No approved admin client accounts yet.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
