<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Printing & Services') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f6f6f6] min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10 flex justify-end">
                <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 hover:bg-gray-50 transition">
                    <span>🛒</span>
                    <span>View Cart</span>
                </a>
            </div>

            <h2 class="text-4xl font-extrabold text-center text-gray-900 mb-8">Our Services</h2>
<<<<<<< Updated upstream

=======
            
>>>>>>> Stashed changes
            @if($services->count() === 0)
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <p class="text-gray-500 italic">No services available at the moment.</p>
                </div>
            @else
                @if($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <p class="font-semibold mb-1">Unable to add item to cart.</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($services as $service)
                        <div class="bg-white border border-gray-200 p-4 hover:shadow-md transition">
                            
                            <a href="{{ route('services.show', $service) }}" class="block group">
                            <div class="relative h-72 w-full bg-gray-100 overflow-hidden">
                                @php
                                    $previewImage = optional($service->activeVariations->first())->variation_image_path
                                        ? asset('storage/' . $service->activeVariations->first()->variation_image_path)
                                        : ($service->image_path ? asset('storage/' . $service->image_path) : null);
                                @endphp
                                @if($previewImage)
                                    <img src="{{ $previewImage }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            </a>

                            <div class="pt-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs tracking-wide uppercase text-gray-500">{{ $service->category }}</p>
                                        <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ \Illuminate\Support\Str::upper($service->name) }}</h3>
                                    </div>
                                    <span class="text-xl text-gray-500">♡</span>
                                </div>

                                @if($service->activeVariations->isNotEmpty())
                                    <form method="POST" action="{{ route('cart.add', $service->id) }}" class="mt-4 space-y-2">
                                        @csrf
                                        <div>
                                            <label class="block text-[11px] uppercase text-gray-500 font-bold mb-1">Package</label>
                                            <select name="service_variation_id" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                @foreach($service->activeVariations as $variation)
                                                    <option value="{{ $variation->id }}">
                                                        {{ $variation->package_type ?: $variation->service_item_id }} ({{ $variation->variation_label ?: 'Default variant' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-[11px] uppercase text-gray-500 font-bold mb-1">Price Type</label>
                                                <select name="price_type" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="retail">Retail</option>
                                                    <option value="bulk">Bulk</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Qty</label>
                                                <input type="number" name="qty" min="1" value="1" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>

                                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-200 text-xs uppercase">
                                            Select Type
                                        </button>
                                    </form>
                                @else
                                    <div class="mt-4 rounded-md bg-yellow-50 border border-yellow-200 px-3 py-2 text-xs text-yellow-700">
                                        No active variants available for this service.
                                    </div>
                                @endif

                                <a href="{{ route('services.show', $service) }}"
                                   class="mt-3 inline-flex w-full justify-center rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    View Details
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $services->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
