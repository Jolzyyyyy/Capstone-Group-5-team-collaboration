<x-app-layout>
    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="relative overflow-hidden bg-[#1f1d1c]">
            <div class="absolute inset-0">
                <img src="{{ asset('images/Homesld2.jpg') }}" alt="" class="h-full w-full object-cover opacity-28">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1f1d1c] via-[#1f1d1c]/92 to-[#1f1d1c]/58"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <p class="text-xs font-black uppercase text-[#ffb970]">{{ $kicker }}</p>
                <div class="mt-3 grid gap-6 lg:grid-cols-[1fr,auto] lg:items-end">
                    <div>
                        <h1 class="max-w-3xl text-4xl font-black text-white sm:text-5xl">{{ $title }}</h1>
                        <p class="mt-4 max-w-3xl text-sm leading-7 text-white/78">{{ $description }}</p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-white/20 bg-white/10 px-4 py-3 text-sm font-black uppercase text-white backdrop-blur transition hover:bg-white/18">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white shadow-lg shadow-orange-950/25 transition hover:bg-[#ff6a00]">
                            Orders
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($cards as $card)
                    <div class="rounded-lg border border-[#eadfd2] bg-white p-5 shadow-sm">
                        <p class="text-xs font-black uppercase text-[#8a6d52]">{{ $card['label'] }}</p>
                        <p class="mt-3 break-words text-2xl font-black text-[#22201f]">{{ $card['value'] }}</p>
                        <p class="mt-2 text-sm text-[#6f675f]">{{ $card['note'] }}</p>
                    </div>
                @endforeach
            </div>

            <section class="mt-8 overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                <div class="border-b border-[#f0e5d8] px-5 py-4">
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">{{ $kicker }}</p>
                    <h2 class="mt-1 text-lg font-black text-[#22201f]">{{ $title }} Records</h2>
                </div>

                <div class="divide-y divide-[#f0e5d8]">
                    @forelse ($rows as $row)
                        <div class="px-5 py-4">
                            <p class="font-black text-[#22201f]">{{ $row['title'] }}</p>
                            <p class="mt-1 break-words text-sm text-[#6f675f]">{{ $row['meta'] }}</p>
                            <p class="mt-1 text-sm font-semibold text-[#8a6d52]">{{ $row['note'] }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                            {{ $emptyMessage }}
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>
</x-app-layout>
