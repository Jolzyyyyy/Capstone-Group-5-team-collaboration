<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">Admin Setup</p>
        <h2 class="mt-2 text-2xl font-black text-slate-900">Complete Your Reference Profile</h2>
        <p class="mt-2 text-sm text-slate-600">
            {{ $adminClient->email }} was invited to the staff portal. Set your password and provide the details needed for system records.
        </p>
    </div>

    <form method="POST" action="{{ route('admin-client-invitations.store', $token) }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="business_name" :value="__('Business / Organization Name')" />
            <x-text-input id="business_name" class="mt-1 block w-full" type="text" name="business_name" :value="old('business_name')" required autofocus />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="contact_person" :value="__('Main Contact Person')" />
            <x-text-input id="contact_person" class="mt-1 block w-full" type="text" name="contact_person" :value="old('contact_person', $adminClient->name)" required />
            <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="contact_number" :value="__('Contact Number')" />
            <x-text-input id="contact_number" class="mt-1 block w-full" type="text" name="contact_number" :value="old('contact_number')" required />
            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="business_address" :value="__('Business Address')" />
            <textarea id="business_address" name="business_address" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('business_address') }}</textarea>
            <x-input-error :messages="$errors->get('business_address')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="reference_notes" :value="__('Reference Notes')" />
            <textarea id="reference_notes" name="reference_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('reference_notes') }}</textarea>
            <x-input-error :messages="$errors->get('reference_notes')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">
                {{ __('Save Reference Profile') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
