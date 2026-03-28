<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * View cart page.
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        $items = [];
        $total = 0;

        foreach ($cart as $serviceId => $row) {
            $lineTotal = $row['price'] * $row['qty'];
            $total += $lineTotal;

            $items[] = [
                'service_id' => $serviceId,
                'name'       => $row['name'],
                'category'   => $row['category'],
                'unit'       => $row['unit'],
                'price'      => $row['price'],
                'price_type' => $row['price_type'], // retail or bulk
                'qty'        => $row['qty'],
                'line_total' => $lineTotal,
                'image_path' => $row['image_path'] ?? null,
            ];
        }

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Add a service to cart (or increase qty).
     * POST /cart/add/{service}
     */
    public function add(Request $request, Service $service)
    {
        abort_if(!$service->is_active, 404);

        $validated = $request->validate([
            'qty' => ['nullable', 'integer', 'min:1', 'max:999'],
            'price_type' => ['nullable', 'in:retail,bulk'],
        ]);

        $qty = (int)($validated['qty'] ?? 1);
        $priceType = $validated['price_type'] ?? 'retail';

        $price = $priceType === 'bulk'
            ? (float)$service->bulk_price
            : (float)$service->retail_price;

        $cart = session()->get('cart', []);

        if (isset($cart[$service->id])) {
            $cart[$service->id]['qty'] += $qty;
            // if user changes price_type, update it too
            $cart[$service->id]['price_type'] = $priceType;
            $cart[$service->id]['price'] = $price;
        } else {
            $cart[$service->id] = [
                'name'       => $service->name,
                'category'   => $service->category,
                'unit'       => $service->unit,
                'price'      => $price,
                'price_type' => $priceType,
                'qty'        => $qty,
                'image_path' => $service->image_path,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Added to cart.');
    }

    /**
     * Update qty/price_type of an item in cart
     * POST /cart/update/{service}
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
            'price_type' => ['required', 'in:retail,bulk'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$service->id])) {
            return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
        }

        $priceType = $validated['price_type'];
        $price = $priceType === 'bulk'
            ? (float)$service->bulk_price
            : (float)$service->retail_price;

        $cart[$service->id]['qty'] = (int)$validated['qty'];
        $cart[$service->id]['price_type'] = $priceType;
        $cart[$service->id]['price'] = $price;

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    /**
     * Remove item from cart
     * POST /cart/remove/{service}
     */
    public function remove(Request $request, Service $service)
    {
        $cart = session()->get('cart', []);

        unset($cart[$service->id]);

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    /**
     * Clear all cart
     * POST /cart/clear
     */
    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    // ============================================================
    // ===================== ✅ OURS BELOW =========================
    // ========== (CHECKOUT/PAYMENT SUPPORT ONLY) ==================
    // ============================================================

    /**
     * ✅ SYNC CART (from LocalStorage -> Laravel Session 'cart')
     * POST /cart/sync
     *
     * Expects JSON:
     * { items: [{ name, qty, unit_price, service_code?, price_type? }] }
     */
    public function syncCart(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.service_code' => ['nullable', 'string'],
            'items.*.price_type' => ['nullable', 'in:retail,bulk'],
        ]);

        $cart = [];

        foreach ($validated['items'] as $idx => $i) {
            $key = $i['service_code'] ?: ('LS-' . $idx . '-' . uniqid());

            $cart[$key] = [
                'name'       => $i['name'],
                'category'   => null,
                'unit'       => null,
                'price'      => (float) $i['unit_price'], // ✅ unit price
                'price_type' => $i['price_type'] ?? 'retail',
                'qty'        => (int) $i['qty'],
                'image_path' => null,
            ];
        }

        session()->put('cart', $cart);

        // important: clear buy_now para cart-based checkout ang priority
        session()->forget('buy_now');

        return response()->json(['ok' => true]);
    }

    /**
     * ✅ BUY NOW (Product Detail -> Laravel Session 'buy_now')
     * POST /cart/buy-now
     *
     * Expects JSON:
     * { name, qty, unit_price, price_type, service_code? }
     */
    public function buyNow(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'price_type' => ['required', 'in:retail,bulk'],
            'service_code' => ['nullable', 'string'],
        ]);

        $key = $validated['service_code'] ?: ('BUY-' . uniqid());

        session()->put('buy_now', [
            $key => [
                'name'       => $validated['name'],
                'category'   => null,
                'unit'       => null,
                'price'      => (float) $validated['unit_price'], // ✅ unit price
                'price_type' => $validated['price_type'],
                'qty'        => (int) $validated['qty'],
                'image_path' => null,
            ]
        ]);

        // ✅ go to SAME checkout page
        return response()->json(['ok' => true]);
    }
}