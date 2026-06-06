<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymongoCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        return redirect('/checkout');
    }

    public function pay(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:gcash,card,grab_pay,paymaya,maya,bank'],
        ]);

        try {
            $checkoutUrl = $this->createCheckoutUrl($request);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', $exception->getMessage());
        }

        return redirect()->away($checkoutUrl);
    }

    public function start(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:gcash,card,grab_pay,paymaya,maya,bank'],
        ]);

        try {
            return response()->json([
                'ok' => true,
                'redirect_url' => $this->createCheckoutUrl($request),
            ]);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    private function createCheckoutUrl(Request $request): string
    {
        $cartItems = $this->getCheckoutItems();

        if (empty($cartItems)) {
            throw new \RuntimeException('No items found for checkout. Please add items first.');
        }

        $cartTotal = $this->computeTotal($cartItems);
        $amountInCentavos = (int) round($cartTotal * 100);

        if ($amountInCentavos < 1) {
            throw new \RuntimeException('Invalid checkout total. Please try again.');
        }

        if (in_array($request->payment_method, ['paymaya', 'maya'], true)) {
            return $this->createMayaCheckoutUrl($request, $cartItems, $cartTotal);
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

        $secretKey = trim((string) config('services.paymongo.secret_key'));
        if (!$secretKey) {
            throw new \RuntimeException('PAYMONGO_SECRET_KEY is missing in .env');
        }

        $successUrl = url('/payment/success');
        $cancelUrl  = url('/payment/cancel');

        $response = Http::withBasicAuth($secretKey, '')
            ->timeout(30)
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
            throw new \RuntimeException('PayMongo error: ' . $response->body());
        }

        $checkoutUrl = data_get($response->json(), 'data.attributes.checkout_url');
        if (!$checkoutUrl) {
            throw new \RuntimeException('No checkout_url returned by PayMongo.');
        }

        return $checkoutUrl;
    }

    public function success(Request $request)
    {
        session()->forget('buy_now');
        session()->forget('cart');
        return redirect('/checkout?payment=success&ref=' . urlencode((string) $request->query('ref', '')));
    }

    public function cancel(Request $request)
    {
        return redirect('/checkout?payment=cancel&ref=' . urlencode((string) $request->query('ref', '')));
    }

    private function mapPaymentMethodTypes(string $method): array
    {
        return match ($method) {
            'gcash' => ['gcash'],
            'card' => ['card'],
            'grab_pay' => ['grab_pay'],
            'paymaya', 'maya' => ['paymaya'],
            'bank' => ['dob'],
            default => ['gcash'],
        };
    }

    private function createMayaCheckoutUrl(Request $request, array $cartItems, float $cartTotal): string
    {
        $publicKey = trim((string) config('services.maya.public_key'));
        $checkoutUrl = trim((string) config('services.maya.checkout_url'));

        if (!$publicKey) {
            throw new \RuntimeException('MAYA_PUBLIC_KEY is missing in .env');
        }
        if (!$checkoutUrl) {
            throw new \RuntimeException('MAYA_CHECKOUT_URL is missing in .env');
        }

        $reference = 'PFY-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
        $items = collect($cartItems)->map(function ($item, $index) {
            $name = (string) ($item['name'] ?? 'Print Item');
            $qty = max(1, (int) ($item['qty'] ?? 1));
            $price = round((float) ($item['price'] ?? 0), 2);

            return [
                'name' => Str::limit($name, 120, ''),
                'code' => 'ITEM-' . ($index + 1),
                'description' => Str::limit($name, 255, ''),
                'quantity' => $qty,
                'amount' => [
                    'value' => $price,
                    'currency' => 'PHP',
                ],
                'totalAmount' => [
                    'value' => round($price * $qty, 2),
                    'currency' => 'PHP',
                ],
            ];
        })->values()->all();

        $user = $request->user();
        $nameParts = preg_split('/\s+/', trim((string) ($user?->name ?: 'Printify Customer'))) ?: [];
        $firstName = Str::limit($nameParts[0] ?? 'Printify', 60, '');
        $lastName = Str::limit(count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : 'Customer', 60, '');
        $payload = [
            'totalAmount' => [
                'value' => round($cartTotal, 2),
                'currency' => 'PHP',
            ],
            'buyer' => [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'contact' => [
                    'email' => $user?->email ?: 'customer@printify.local',
                ],
            ],
            'items' => $items,
            'requestReferenceNumber' => $reference,
            'redirectUrl' => [
                'success' => url('/payment/success?ref=' . urlencode($reference)),
                'failure' => url('/payment/cancel?ref=' . urlencode($reference)),
                'cancel' => url('/payment/cancel?ref=' . urlencode($reference)),
            ],
        ];

        $response = Http::withBasicAuth($publicKey, '')
            ->timeout(30)
            ->acceptJson()
            ->asJson()
            ->post($checkoutUrl, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('Maya checkout error: ' . $response->body());
        }

        $body = $response->json();
        $redirectUrl = data_get($body, 'redirectUrl')
            ?: data_get($body, 'checkoutUrl')
            ?: data_get($body, 'data.redirectUrl');

        if (!$redirectUrl) {
            throw new \RuntimeException('No redirect URL returned by Maya.');
        }

        return $redirectUrl;
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
