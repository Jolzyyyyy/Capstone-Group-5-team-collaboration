<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cart = [];
        $itemsCount = 0;
        $total = 0;

        foreach ($rawCart as $serviceId => $row) {
            $qty = (int)($row['qty'] ?? 1);
            $unitPrice = (float)($row['price'] ?? 0);
            $subtotal = $qty * $unitPrice;

            $itemsCount += $qty;
            $total += $subtotal;

            $cart[] = [
                'service_id'  => (int)$serviceId,
                'name'        => $row['name'] ?? 'Service',
                'category'    => $row['category'] ?? '',
                'unit'        => $row['unit'] ?? '',
                'price_type'  => $row['price_type'] ?? 'retail',
                'unit_price'  => $unitPrice,
                'qty'         => $qty,
                'subtotal'    => $subtotal,
            ];
        }

        $summary = [
            'items_count' => $itemsCount,
            'total'       => $total,
        ];

        return view('checkout.index', compact('cart', 'summary'));
    }

    public function place(Request $request)
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'print_zip'      => ['required', 'file', 'mimes:zip', 'max:51200'],
        ], [
            'print_zip.required' => 'Please upload a ZIP file before placing the order.',
        ]);

        return DB::transaction(function () use ($request, $rawCart) {

            $order = Order::create([
                'user_id'        => auth()->id(),
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'status'         => 'Pending',
                'total_price'    => 0,
            ]);

            foreach ($rawCart as $serviceId => $row) {
                $service = Service::findOrFail($serviceId);

                $qty = (int)($row['qty'] ?? 1);
                $unitPrice = (float)($row['price'] ?? 0);
                $subtotal = $qty * $unitPrice;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'service_id'   => $service->id,
                    'service_name' => $row['name'] ?? $service->name,
                    'unit_price'   => $unitPrice,
                    'quantity'     => $qty,
                    'subtotal'     => $subtotal,
                ]);
            }

            $order->recomputeTotal();

            $zip = $request->file('print_zip');
            $path = $zip->store("order-files/{$order->id}", 'public');

            $order->files()->create([
                'original_name' => $zip->getClientOriginalName(),
                'path'          => $path,
                'mime'          => $zip->getClientMimeType(),
                'size'          => $zip->getSize(),
            ]);

            session()->forget('cart');

            return redirect()
                ->route('orders.my.show', $order->id)
                ->with('success', 'Order placed successfully! ZIP attached ✅');
        });
    }
}
