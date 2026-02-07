<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Checkout is for logged-in customers only.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show checkout page (summary + customer details).
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $summary = $this->summary($cart);

        return view('checkout.index', [
            'cart' => $cart,
            'summary' => $summary,
        ]);
    }

    /**
     * Place order: create orders + order_items from session cart.
     */
    public function place(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
        ]);

        // Safety: re-check service prices from DB so user cannot cheat by editing session
        $serviceIds = array_keys($cart);
        $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'        => auth()->id(), // make sure orders table has user_id
                'customer_name'  => $validated['customer_name'],
                'customer_email' => $validated['customer_email'] ?? null,
                'status'         => 'Pending',
                'total_price'    => 0,
            ]);

            $total = 0;

            foreach ($cart as $serviceId => $item) {
                $service = $services->get($serviceId);

                // If service missing/inactive, stop checkout (prevents ordering deleted service)
                if (!$service || !$service->is_active) {
                    throw new \Exception("Service not available: {$item['name']}");
                }

                $priceType = $item['price_type'] ?? 'retail';
                $qty = (int)($item['qty'] ?? 1);
                if ($qty < 1) $qty = 1;

                $unitPrice = $priceType === 'bulk'
                    ? (float)$service->bulk_price
                    : (float)$service->retail_price;

                // bulk fallback
                if ($priceType === 'bulk' && (float)$service->bulk_price <= 0) {
                    $priceType = 'retail';
                    $unitPrice = (float)$service->retail_price;
                }

                $subtotal = $unitPrice * $qty;
                $total += $subtotal;

                OrderItem::create([
                    'order_id'    => $order->id,
                    'service_id'  => $service->id,
                    'service_name'=> $service->name, // keep snapshot even if service changes later
                    'price_type'  => $priceType,
                    'unit_price'  => $unitPrice,
                    'quantity'    => $qty,
                    'subtotal'    => $subtotal,
                ]);
            }

            $order->update([
                'total_price' => $total,
            ]);

            // clear cart after successful checkout
            session()->forget('cart');

            DB::commit();

            // Next step: payment (later). For now show order details page.
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('checkout.index')->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    private function summary(array $cart): array
    {
        $itemsCount = 0;
        $total = 0;

        foreach ($cart as $item) {
            $itemsCount += (int)($item['qty'] ?? 0);
            $total += (float)($item['subtotal'] ?? 0);
        }

        return [
            'items_count' => $itemsCount,
            'total' => $total,
        ];
    }
}
