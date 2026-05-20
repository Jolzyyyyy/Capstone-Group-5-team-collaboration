<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-6">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-600">Admin Client Records</p>
            <h1 class="mt-2 text-3xl font-black text-slate-900 tracking-tight">Reference Profile</h1>
            <p class="mt-2 text-sm text-slate-600">Keep these details accurate for future system reference and support.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.admin-client-profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="business_name" :value="__('Business / Organization Name')" />
                    <x-text-input id="business_name" class="mt-1 block w-full" type="text" name="business_name" :value="old('business_name', $profile->business_name)" required autofocus />
                    <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="contact_person" :value="__('Main Contact Person')" />
                    <x-text-input id="contact_person" class="mt-1 block w-full" type="text" name="contact_person" :value="old('contact_person', $profile->contact_person)" required />
                    <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="contact_number" :value="__('Contact Number')" />
                    <x-text-input id="contact_number" class="mt-1 block w-full" type="text" name="contact_number" :value="old('contact_number', $profile->contact_number)" required />
                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="business_address" :value="__('Business Address')" />
                    <textarea id="business_address" name="business_address" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('business_address', $profile->business_address) }}</textarea>
                    <x-input-error :messages="$errors->get('business_address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="reference_notes" :value="__('Reference Notes')" />
                    <textarea id="reference_notes" name="reference_notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('reference_notes', $profile->reference_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('reference_notes')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between gap-4 pt-2">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-slate-500 underline">Back to dashboard</a>
                    <x-primary-button>
                        {{ __('Save Profile') }}
                    </x-primary-button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
