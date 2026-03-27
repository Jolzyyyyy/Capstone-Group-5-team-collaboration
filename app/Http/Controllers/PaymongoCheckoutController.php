<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymongoCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cartItems = $this->getCheckoutItems();

        if (empty($cartItems)) {
            return view('payment.checkout', [
                'cartItems' => [],
                'cartTotal' => 0,
                'emptyMessage' => 'Your cart is empty. Please add items before checkout.',
            ]);
        }

        $cartTotal = $this->computeTotal($cartItems);

        return view('payment.checkout', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'emptyMessage' => null,
        ]);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:gcash,card,grab_pay,paymaya'],
        ]);

        $cartItems = $this->getCheckoutItems();

        if (empty($cartItems)) {
            return back()->with('error', 'No items found for checkout. Please add items first.');
        }

        $cartTotal = $this->computeTotal($cartItems);
        $amountInCentavos = (int) round($cartTotal * 100);

        if ($amountInCentavos < 1) {
            return back()->with('error', 'Invalid checkout total. Please try again.');
        }

        $lineItems = collect($cartItems)->map(function ($item) {
            $name = $item['name'] ?? 'Item';
            $qty  = (int) ($item['qty'] ?? 1);
            $price = (float) ($item['price'] ?? 0);

            return [
                'name' => $name,
                'quantity' => max(1, $qty),
                'amount' => (int) round($price * 100), // unit price centavos
                'currency' => 'PHP',
            ];
        })->values()->all();

        $secretKey = env('PAYMONGO_SECRET_KEY');
        if (!$secretKey) {
            return back()->with('error', 'PAYMONGO_SECRET_KEY is missing in .env');
        }

        $successUrl = url('/payment/success');
        $cancelUrl  = url('/payment/cancel');

        $response = Http::withBasicAuth($secretKey, '')
            ->acceptJson()
            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                'data' => [
                    'attributes' => [
                        'send_email_receipt' => false,
                        'show_description' => true,
                        'show_line_items' => true,
                        'line_items' => $lineItems,
                        'payment_method_types' => $this->mapPaymentMethodTypes($request->payment_method),
                        'description' => 'Order Payment',
                        'success_url' => $successUrl,
                        'cancel_url' => $cancelUrl,
                        'amount' => $amountInCentavos,
                        'currency' => 'PHP',
                    ],
                ],
            ]);

        if (!$response->successful()) {
            return back()->with('error', 'PayMongo error: ' . $response->body());
        }

        $checkoutUrl = data_get($response->json(), 'data.attributes.checkout_url');
        if (!$checkoutUrl) {
            return back()->with('error', 'No checkout_url returned by PayMongo.');
        }

        return redirect()->away($checkoutUrl);
    }

    public function success(Request $request)
    {
        session()->forget('buy_now');
        session()->forget('cart');
        return view('payment.success');
    }

    public function cancel(Request $request)
    {
        return view('payment.cancel');
    }

    private function mapPaymentMethodTypes(string $method): array
    {
        return match ($method) {
            'gcash' => ['gcash'],
            'card' => ['card'],
            'grab_pay' => ['grab_pay'],
            'paymaya' => ['paymaya'],
            default => ['gcash'],
        };
    }

    /**
     * ✅ Reads the correct session keys (BUY NOW first, then CART)
     */
    private function getCheckoutItems(): array
    {
        // Priority: buy_now > cart
        $items = session('buy_now');
        if (!empty($items) && is_array($items)) {
            return $this->normalizeItemsFromCartStructure($items);
        }

        $items = session('cart', []);
        if (!empty($items) && is_array($items)) {
            return $this->normalizeItemsFromCartStructure($items);
        }

        return [];
    }

    /**
     * session('cart') / session('buy_now') structure:
     * [
     *   key => ['name','price','price_type','qty',...]
     * ]
     */
    private function normalizeItemsFromCartStructure(array $items): array
    {
        return collect($items)->values()->map(function ($item) {
            return [
                'name' => $item['name'] ?? 'Item',
                'price' => (float) ($item['price'] ?? 0), // ✅ unit price
                'qty' => (int) ($item['qty'] ?? 1),
            ];
        })->values()->all();
    }

    private function computeTotal(array $items): float
    {
        return (float) collect($items)->sum(fn ($i) => ((float) $i['price']) * ((int) $i['qty']));
    }
}