<x-app-layout>
    @php
        $canManageServices = Auth::user()?->isDeveloper();
    @endphp

    <div class="min-h-screen bg-[#f7f4ef]" style="font-family: 'Poppins', sans-serif;">
        <section class="border-b border-[#eadfd2] bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-8">
                <div>
                    <p class="text-xs font-black uppercase text-[#ff8d2a]">Service Database</p>
                    <h1 class="mt-2 text-3xl font-black text-[#22201f]">Service Catalog</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#6f675f]">Manage the service records customers can browse and staff can connect to orders.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-[#eadfd2] bg-white px-4 py-3 text-sm font-black uppercase text-[#22201f] transition hover:border-[#ffb970] hover:bg-[#fff8ef]">Dashboard</a>
                    @if ($canManageServices)
                        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center justify-center rounded-lg bg-[#ff8d2a] px-4 py-3 text-sm font-black uppercase text-white transition hover:bg-[#ff6a00]">Add Service</a>
                    @endif
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-lg border border-[#eadfd2] bg-white shadow-sm">
                <div class="grid border-b border-[#f0e5d8] px-5 py-3 text-xs font-black uppercase text-[#8a6d52] md:grid-cols-[0.7fr,1.2fr,1fr,1fr,0.8fr,1fr]">
                    <div>ID</div>
                    <div>Service</div>
                    <div>Category</div>
                    <div>Variations</div>
                    <div>Status</div>
                    <div class="text-right">Actions</div>
                </div>

                <div class="divide-y divide-[#f0e5d8]">
                    @forelse ($services as $service)
                        <div class="grid gap-4 px-5 py-4 text-sm md:grid-cols-[0.7fr,1.2fr,1fr,1fr,0.8fr,1fr] md:items-center">
                            <div class="font-black text-[#22201f]">#{{ $service->id }}</div>
                            <div>
                                <p class="font-black text-[#22201f]">{{ $service->name }}</p>
                                <p class="text-xs text-[#6f675f]">{{ $service->description ? \Illuminate\Support\Str::limit($service->description, 70) : 'No description' }}</p>
                            </div>
                            <div class="text-[#6f675f]">{{ $service->category ?? '-' }}</div>
                            <div class="text-[#6f675f]">{{ $service->active_variations_count }} active / {{ $service->variations_count }} total</div>
                            <div>
                                <span class="rounded-lg px-3 py-1 text-xs font-black uppercase {{ $service->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex flex-wrap justify-start gap-2 md:justify-end">
                                @if ($canManageServices)
                                    <a href="{{ route('admin.services.edit', $service) }}" class="rounded-lg border border-[#eadfd2] px-3 py-2 text-xs font-black uppercase text-[#22201f] transition hover:bg-[#fff8ef]">Edit</a>
                                    <form action="{{ route('admin.services.toggle', $service) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg border border-[#eadfd2] px-3 py-2 text-xs font-black uppercase text-[#22201f] transition hover:bg-[#fff8ef]">
                                            {{ $service->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-semibold text-[#6f675f]">View only</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center text-sm font-semibold text-[#6f675f]">
                            No services found.
                        </div>
                    @endforelse
                </div>
            </section>

            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </main>
    </div>
</x-app-layout>
