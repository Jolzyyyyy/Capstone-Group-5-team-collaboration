<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceVariation;
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

        foreach ($cart as $cartKey => $row) {
            $lineTotal = $row['price'] * $row['qty'];
            $total += $lineTotal;

            $items[] = [
                'cart_key'         => $cartKey,
                'service_id'       => $row['service_id'],
                'variation_id'     => $row['variation_id'],
                'service_item_id'  => $row['service_item_id'],
                'name'             => $row['name'],
                'category'         => $row['category'],
                'variation_label'  => $row['variation_label'],
                'unit'             => $row['unit'],
                'price'            => $row['price'],
                'price_type'       => $row['price_type'],
                'qty'              => $row['qty'],
                'line_total'       => $lineTotal,
                'image_path'       => $row['image_path'] ?? null,
            ];
        }

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Add a variation to cart (or increase qty).
     * POST /cart/add/{service}
     */
    public function add(Request $request, Service $service)
    {
        abort_if(!$service->is_active, 404);

        $validated = $request->validate([
            'service_variation_id' => ['required', 'exists:service_variations,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:999'],
            'price_type' => ['nullable', 'in:retail,bulk'],
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $priceType = $validated['price_type'] ?? 'retail';

        $variation = ServiceVariation::where('id', $validated['service_variation_id'])
            ->where('service_id', $service->id)
            ->where('is_active', true)
            ->firstOrFail();

        $price = $priceType === 'bulk'
            ? (float) $variation->bulk_price
            : (float) $variation->retail_price;

        $cart = session()->get('cart', []);

        // unique cart key per service variation + price type
        $cartKey = $service->id . '_' . $variation->id . '_' . $priceType;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
            $cart[$cartKey]['price'] = $price;
            $cart[$cartKey]['price_type'] = $priceType;
        } else {
            $cart[$cartKey] = [
                'service_id'      => $service->id,
                'variation_id'    => $variation->id,
                'service_item_id' => $variation->service_item_id,
                'name'            => $service->name,
                'category'        => $service->category,
                'variation_label' => $variation->variation_label,
                'unit'            => $service->unit,
                'price'           => $price,
                'price_type'      => $priceType,
                'qty'             => $qty,
                'image_path'      => $service->image_path,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Added to cart.');
    }

    /**
     * Update qty/price_type of a cart item
     * POST /cart/update/{cartKey}
     */
    public function update(Request $request, string $cartKey)
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
            'price_type' => ['required', 'in:retail,bulk'],
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$cartKey])) {
            return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
        }

        $variation = ServiceVariation::find($cart[$cartKey]['variation_id']);

        if (!$variation || !$variation->is_active) {
            return redirect()->route('cart.index')->with('error', 'Selected variation is no longer available.');
        }

        $newPriceType = $validated['price_type'];
        $newPrice = $newPriceType === 'bulk'
            ? (float) $variation->bulk_price
            : (float) $variation->retail_price;

        // if price type changes, key should also change
        $newCartKey = $cart[$cartKey]['service_id'] . '_' . $variation->id . '_' . $newPriceType;

        $updatedRow = $cart[$cartKey];
        $updatedRow['qty'] = (int) $validated['qty'];
        $updatedRow['price_type'] = $newPriceType;
        $updatedRow['price'] = $newPrice;

        unset($cart[$cartKey]);

        if (isset($cart[$newCartKey])) {
            $cart[$newCartKey]['qty'] += $updatedRow['qty'];
            $cart[$newCartKey]['price'] = $updatedRow['price'];
            $cart[$newCartKey]['price_type'] = $updatedRow['price_type'];
        } else {
            $cart[$newCartKey] = $updatedRow;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    /**
     * Remove item from cart
     * POST /cart/remove/{cartKey}
     */
    public function remove(Request $request, string $cartKey)
    {
        $cart = session()->get('cart', []);

        unset($cart[$cartKey]);

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