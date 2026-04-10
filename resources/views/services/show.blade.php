<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Details') }}
        </h2>
    </x-slot>

    @php
        $variants = $service->activeVariations->map(fn ($variation) => [
            'id' => $variation->id,
            'service_item_id' => $variation->service_item_id,
            'printing_category' => $variation->printing_category ?? 'General',
            'color_mode' => $variation->color_mode ?? 'Default',
            'product_size' => $variation->product_size ?? 'Default',
            'finish_type' => $variation->finish_type ?? 'Standard',
            'package_type' => $variation->package_type ?? 'Package',
            'variation_label' => $variation->variation_label ?: 'Default variant',
            'retail_price' => (float) $variation->retail_price,
            'bulk_price' => (float) $variation->bulk_price,
        ])->values();
    @endphp

    <div class="py-10 bg-[#f6f6f6] min-h-screen"
         x-data="{
            variants: @js($variants),
            activeIndex: 0,
            qty: 1,
            priceType: 'retail',
            get current() { return this.variants[this.activeIndex] ?? null; },
            formatMoney(value) { return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value || 0); },
            unitPrice() {
                if (!this.current) return 0;
                return this.priceType === 'bulk' ? this.current.bulk_price : this.current.retail_price;
            },
            totalPrice() { return this.unitPrice() * this.qty; },
            increaseQty() { this.qty += 1; },
            decreaseQty() { if (this.qty > 1) this.qty -= 1; },
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="grid lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-2 space-y-4" x-show="variants.length">
                        <template x-for="(variant, index) in variants" :key="variant.id">
                            <button type="button"
                                    class="w-full border rounded-xl p-2 text-left transition"
                                    :class="activeIndex === index ? 'border-red-500 ring-2 ring-red-100' : 'border-gray-200 hover:border-gray-300'"
                                    @click="activeIndex = index">
                                <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/300x180?text=No+Image' }}"
                                     alt="{{ $service->name }}"
                                     class="w-full h-20 object-cover rounded-md">
                                <p class="mt-2 text-xs font-semibold text-gray-700" x-text="variant.package_type"></p>
                                <p class="text-[11px] text-gray-500 truncate" x-text="variant.product_size"></p>
                            </button>
                        </template>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="border border-gray-200 rounded-2xl p-4 h-full">
                            <div class="relative">
                                <img src="{{ $service->image_path ? asset('storage/' . $service->image_path) : 'https://via.placeholder.com/900x640?text=No+Image' }}"
                                     alt="{{ $service->name }}"
                                     class="w-full h-[450px] object-cover rounded-xl">
                            </div>

                            <div class="mt-4 rounded-xl bg-gray-50 border border-gray-200 p-4">
                                <h3 class="text-4xl font-bold tracking-tight text-gray-900 uppercase">{{ $service->name }}</h3>
                                <p class="mt-1 text-sm text-gray-600" x-text="current ? current.variation_label : ''"></p>
                                <ul class="mt-3 text-sm text-gray-700 list-disc list-inside">
                                    <li x-text="'Finish: ' + (current ? current.finish_type : 'Standard')"></li>
                                    <li x-text="'Printing Category: ' + (current ? current.printing_category : 'General')"></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        @if($service->activeVariations->isEmpty())
                            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-yellow-700">
                                No active variants are available for this service right now.
                            </div>
                        @else
                            <form method="POST" action="{{ route('cart.add', $service) }}" class="space-y-5">
                                @csrf

                                <div>
                                    <h1 class="text-5xl font-black leading-none uppercase">{{ $service->name }}</h1>
                                    <p class="mt-2 text-green-600 font-semibold">● In Stock</p>
                                </div>

                                <input type="hidden" name="service_variation_id" :value="current ? current.id : ''">
                                <input type="hidden" name="qty" :value="qty">
                                <input type="hidden" name="price_type" :value="priceType">

                                <div class="border-t border-b border-gray-200 py-4 space-y-4">
                                    <div class="grid grid-cols-2 gap-3 items-center">
                                        <label class="text-sm font-semibold text-gray-700">Printing Category</label>
                                        <select class="rounded-xl border-gray-300 bg-gray-50" x-model="activeIndex">
                                            <template x-for="(variant, index) in variants" :key="'cat-' + variant.id">
                                                <option :value="index" x-text="variant.printing_category"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 items-center">
                                        <label class="text-sm font-semibold text-gray-700">Color Variation</label>
                                        <input type="text" class="rounded-xl border-gray-300 bg-gray-50" :value="current ? current.color_mode : ''" readonly>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 items-center">
                                        <label class="text-sm font-semibold text-gray-700">Paper Size / Package</label>
                                        <input type="text" class="rounded-xl border-gray-300 bg-gray-50" :value="current ? (current.product_size + ' • ' + current.package_type) : ''" readonly>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 items-center">
                                        <label class="text-sm font-semibold text-gray-700">Quantity</label>
                                        <div class="flex rounded-xl border border-gray-300 overflow-hidden">
                                            <button type="button" class="w-10 bg-gray-50 hover:bg-gray-100" @click="decreaseQty()">-</button>
                                            <input type="number" x-model.number="qty" min="1" class="w-full border-0 text-center focus:ring-0">
                                            <button type="button" class="w-10 bg-gray-50 hover:bg-gray-100" @click="increaseQty()">+</button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 items-center">
                                        <label class="text-sm font-semibold text-gray-700">File Upload</label>
                                        <input type="text" class="rounded-xl border-gray-300 bg-gray-50" value="Upload during checkout" readonly>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <p class="text-sm text-gray-500">Service ID: <span class="font-semibold text-gray-700" x-text="current ? current.service_item_id : 'N/A'"></span></p>

                                    <div class="rounded-xl border border-gray-200 p-4 bg-gray-50">
                                        <div class="flex items-center gap-4 text-sm">
                                            <label class="inline-flex items-center gap-2">
                                                <input type="radio" x-model="priceType" value="retail" class="text-red-600">
                                                <span>Retail</span>
                                            </label>
                                            <label class="inline-flex items-center gap-2">
                                                <input type="radio" x-model="priceType" value="bulk" class="text-red-600">
                                                <span>Bulk</span>
                                            </label>
                                        </div>

                                        <p class="mt-3 text-lg">Unit Price: <span class="font-bold text-red-600" x-text="formatMoney(unitPrice())"></span></p>
                                        <p class="text-4xl font-black mt-1 text-red-700" x-text="formatMoney(totalPrice())"></p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit" class="px-5 py-3 rounded-lg bg-red-600 text-white font-bold hover:bg-red-700 uppercase text-sm">
                                        Add to Cart
                                    </button>
                                    <a href="{{ route('services.index') }}" class="px-5 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">
                                        Back
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
