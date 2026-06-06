<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Printing & Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150 shadow-sm">
                    🛒 View Cart
                </a>
            </div>

            @if($services->count() === 0)
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <p class="text-gray-500 italic">No services available at the moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($services as $service)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                            
                            <div class="relative h-48 w-full bg-gray-200">
                                @if($service->image_path)
                                    <img src="{{ asset('storage/'.$service->image_path) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="p-5">
                                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">{{ $service->category }}</span>
                                <h3 class="text-lg font-bold text-gray-800 mt-1">{{ $service->name }}</h3>
                                
                                <div class="mt-3 space-y-1">
                                    <p class="text-sm text-gray-600">Retail: <span class="font-bold text-gray-900">₱{{ number_format($service->retail_price, 2) }}</span></p>
                                    <p class="text-sm text-gray-600">Bulk: <span class="font-bold text-gray-900">₱{{ number_format($service->bulk_price, 2) }}</span></p>
                                </div>

                                <form method="POST" action="{{ route('cart.add', $service->id) }}" class="mt-4">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-2 mb-3">
                                        <div>
                                            <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Type</label>
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

                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 text-sm shadow-sm">
                                        ➕ Add to Cart
                                    </button>
                                </form>
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