<x-app-layout>
    @php
        $statuses = ['Pending', 'For Verification', 'Processing', 'Ready', 'Completed', 'Cancelled'];
    @endphp

    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="border-b border-[#eadfd2] bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-8">
                <div>
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">Order Status</p>
                    <h1 class="mt-2 text-3xl font-black text-[#22201f]">Update Order #{{ $order->id }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#6f675f]">Move the order through verification, production, ready, and completion states.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center justify-center rounded-lg border border-[#eadfd2] bg-white px-4 py-3 text-sm font-black uppercase text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">Back to Order</a>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg border border-[#eadfd2] bg-white px-4 py-3 text-sm font-black uppercase text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">All Orders</a>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
            <section class="rounded-lg border border-[#eadfd2] bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-[#d8c8b7] bg-white text-[#22201f] shadow-sm focus:border-[#ff8d2a] focus:ring-[#ff8d2a]">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="rounded-lg bg-[#f7f4ef] p-4 text-sm text-[#6f675f]">
                        <p class="font-black text-[#22201f]">{{ $order->customer_name }}</p>
                        <p class="mt-1">{{ $order->customer_email ?? 'No email recorded' }}</p>
                    </div>

                    <x-primary-button>
                        {{ __('Save Status') }}
                    </x-primary-button>
                </form>
            </section>
        </main>
    </div>
</x-app-layout>
