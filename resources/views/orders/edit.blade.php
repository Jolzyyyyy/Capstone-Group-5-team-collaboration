<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Order Status (Admin)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        .btn { padding: 10px 14px; border: 1px solid #111; background: #111; color: #fff; text-decoration: none; display:inline-block; cursor:pointer; }
        .btn-outline { background: #fff; color: #111; }
        .row { display:flex; gap: 10px; margin-top: 12px; align-items: center; flex-wrap: wrap; }
        .box { border: 1px solid #ddd; border-radius: 10px; padding: 16px; margin-top: 14px; max-width: 520px; }
        select { padding: 10px; width: 100%; }
        .msg { padding: 10px; margin-top: 12px; border-radius: 6px; }
        .error { background: #ffecec; border: 1px solid #ff9090; }
        label { display:block; margin-bottom: 8px; }
    </style>
</head>
<body>

<h1>Edit Order #{{ $order->id }} (Admin)</h1>

<div class="row">
    <a class="btn btn-outline" href="{{ route('admin.orders.show', $order) }}">Back to Order</a>
    <a class="btn btn-outline" href="{{ route('admin.orders.index') }}">Back to Orders</a>
</div>

<div class="box">
    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
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
