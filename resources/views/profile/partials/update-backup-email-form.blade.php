<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Backup Email') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add a separate recovery contact for account support and future security checks.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.backup-email.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="backup_email" :value="__('Backup Email')" />
            <x-text-input id="backup_email" name="backup_email" type="email" class="mt-1 block w-full" :value="old('backup_email', $user->backup_email)" autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->backupEmail->get('backup_email')" />
        </div>

        <div class="rounded-lg border border-[#eadfd2] bg-[#fffaf4] px-4 py-3 text-sm text-[#6f675f]">
            <p class="font-semibold text-[#22201f]">{{ __('Primary email') }}</p>
            <p class="mt-1">{{ $user->email }}</p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'backup-email-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
