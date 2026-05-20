<section>
    <header class="mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wide">
            {{ __('Account Information') }}
        </h3>
        <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
            {{ __("Update your profile details and email address.") }}
        </p>
    </header>

    {{-- Tinanggal ang error-prone send-verification form --}}

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-700">
            <div>
                <x-input-label for="name" :value="__('Name')" class="dark:text-gray-300" />
                <x-text-input 
                    id="name" 
                    name="name" 
                    type="text" 
                    class="mt-1 block w-full dark:bg-gray-700 dark:text-white" 
                    :value="old('name', $user->name)" 
                    required 
                    autofocus 
                    autocomplete="name" 
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
                <x-text-input 
                    id="email" 
                    name="email" 
                    type="email" 
                    class="mt-1 block w-full dark:bg-gray-700 dark:text-white" 
                    :value="old('email', $user->email)" 
                    required 
                    autocomplete="username" 
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="flex items-center gap-4 border-t dark:border-gray-800 pt-6">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition duration-150">
                {{ __('Save Information') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p 
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400 font-bold flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Successfully Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>