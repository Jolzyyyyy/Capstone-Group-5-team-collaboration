<section>
    <header>
        <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest">
            {{ __('Backup Email Address') }}
        </h2>

        <p class="mt-1 text-xs text-gray-500 uppercase tracking-wider">
            {{ __("Add a secondary email to recover your account if you lose access to your primary email.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.backup-email.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="backup_email" :value="__('Backup Email')" />
            <x-text-input id="backup_email" name="backup_email" type="email" class="mt-1 block w-full" :value="old('backup_email', $user->backup_email)" placeholder="recovery@example.com" />
            <x-input-error class="mt-2" :messages="$errors->get('backup_email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Backup') }}</x-primary-button>

            @if (session('status') === 'backup-email-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-gray-600 uppercase"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>