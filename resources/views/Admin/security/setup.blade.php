<x-app-layout>
    <div class="min-h-[calc(100vh-64px)] bg-[#f7f4ef] px-4 py-12">
        <div class="mx-auto max-w-xl rounded-lg border border-[#eadfd2] bg-white p-8 text-center shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.24em] text-[#ff8d2a]">Staff Security</p>
            <h1 class="mt-3 text-2xl font-black text-[#22201f]">Email OTP Is Active</h1>
            <p class="mt-3 text-sm leading-6 text-[#6f675f]">
                Authenticator app setup has been replaced by email verification for the staff and developer portal.
                You will receive a 6-digit code by email each time you start a new portal session.
            </p>

            <a href="{{ route('admin.dashboard') }}" class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-5 py-3 text-sm font-black uppercase tracking-[0.14em] text-white transition hover:bg-[#ff6a00]">
                Back to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
