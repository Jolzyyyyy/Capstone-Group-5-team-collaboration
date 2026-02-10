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
        // You probably already have a checkout view.
        // Just return it as you currently do.
        return view('checkout.index');
    }

    /**
     * Place order (ZIP required before submitting).
     * POST /checkout/place-order
     */
    public function placeOrder(Request $request)
    {
        // ✅ ZIP REQUIRED
        $request->validate([
            'print_zip' => ['required', 'file', 'mimes:zip', 'max:51200'], // 50MB
        ], [
            'print_zip.required' => 'Please upload a ZIP file before placing the order.',
            'print_zip.mimes' => 'The uploaded file must be a .zip file.',
            'print_zip.max' => 'The ZIP file must be 50MB or below.',
        ]);

        // ✅ Cart required
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        return DB::transaction(function () use ($request, $cart) {

            // ✅ Create order first
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => auth()->user()->name ?? '',
                'customer_email' => auth()->user()->email ?? '',
                'status' => 'Pending',
                'total_price' => 0,
            ]);

            // ✅ Create order items (SERVICE-based + with subtotal)
            foreach ($cart as $item) {
                $serviceId = $item['id'];
                $qty = $item['quantity'] ?? 1;

                $service = Service::findOrFail($serviceId);

                // IMPORTANT: adjust if your service price column is NOT `price`
                $unitPrice = $service->price;

                $subtotal = $qty * $unitPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            // ✅ compute total from subtotal
            $order->recomputeTotal();

            // ✅ Store ZIP
            $zip = $request->file('print_zip');
            $path = $zip->store("order-files/{$order->id}", 'public');

            // ✅ Save file record in order_files
            $order->files()->create([
                'original_name' => $zip->getClientOriginalName(),
                'path' => $path,
                'mime' => $zip->getClientMimeType(),
                'size' => $zip->getSize(),
            ]);

            // ✅ Clear cart
            session()->forget('cart');

            // ✅ Redirect customer to their order details (uses orders.show)
            return redirect()
                ->route('orders.my.show', $order->id)
                ->with('success', 'Order placed successfully! ZIP attached ✅');
        });
    }
}
