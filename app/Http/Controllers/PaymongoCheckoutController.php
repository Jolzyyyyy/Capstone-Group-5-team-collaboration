<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymongoCheckoutController extends Controller
{
    public function checkout(Request $request, ?Order $order = null)
    {
        if (!$order) {
            $storedOrderId = session('payment_order_id');

            if ($storedOrderId) {
                $storedOrder = Order::query()->find($storedOrderId);

                if ($storedOrder && $this->customerOwnsOrder($request, $storedOrder)) {
                    return redirect()->route('payment.checkout', $storedOrder);
                }
            }

            if (!empty($this->getCheckoutItems())) {
                return redirect()
                    ->route('checkout.index')
                    ->with('success', 'Confirm your details before continuing to payment.');
            }

            return view('payment.checkout', [
                'order' => null,
                'cartItems' => [],
                'cartTotal' => 0,
                'emptyMessage' => 'Your cart is empty. Please add items before checkout.',
            ]);
        }

        $this->authorizeCustomerOrder($request, $order);
        $order->load('items');

        return view('payment.checkout', [
            'order' => $order,
            'cartItems' => $this->itemsFromOrder($order),
            'cartTotal' => (float) $order->total_price,
            'emptyMessage' => $order->items->isEmpty()
                ? 'This order has no saved items. Please create a new checkout.'
                : null,
        ]);
    }

    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => ['required', 'in:gcash,card,grab_pay,paymaya'],
        ]);

        return $this->startPayment($request, $order, $request->payment_method);
    }

    public function startPayment(Request $request, Order $order, string $paymentMethod)
    {
        $this->authorizeCustomerOrder($request, $order);

        if ($order->payment_status === Order::PAYMENT_PAID) {
            return redirect()
                ->route('orders.my.show', $order)
                ->with('success', 'This order has already been paid.');
        }

        $order->load('items');

        if ($order->items->isEmpty()) {
            return back()->with('error', 'No saved order items found for payment. Please create a new checkout.');
        }

        $amountInCentavos = (int) round(((float) $order->total_price) * 100);

        if ($amountInCentavos < 1) {
            return back()->with('error', 'Invalid checkout total. Please try again.');
        }

        $secretKey = $this->paymongoSecretKey();
        if (!$secretKey) {
            return redirect()
                ->route('payment.checkout', $order)
                ->with('error', 'PAYMONGO_SECRET_KEY is missing in .env. The order was saved, but online payment cannot start yet.');
        }

        $reference = $this->paymongoOrderReference($order);
        $payload = [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => false,
                    'show_description' => true,
                    'show_line_items' => true,
                    'line_items' => $this->lineItemsFromOrder($order),
                    'payment_method_types' => $this->mapPaymentMethodTypes($paymentMethod),
                    'description' => "Order #{$order->id} payment",
                    'success_url' => route('payment.success', $order),
                    'cancel_url' => route('payment.cancel', $order),
                    'amount' => $amountInCentavos,
                    'currency' => 'PHP',
                    'reference_number' => $reference,
                    'metadata' => [
                        'order_id' => (string) $order->id,
                        'customer_id' => (string) $order->user_id,
                        'pm_reference_number' => $reference,
                    ],
                ],
            ],
        ];

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(15)
                ->post('https://api.paymongo.com/v1/checkout_sessions', $payload);
        } catch (ConnectionException $exception) {
            Log::error('PayMongo connection failed for order '.$order->id.': '.$exception->getMessage());

            return redirect()
                ->route('payment.checkout', $order)
                ->with('error', 'PayMongo did not respond in time. Please try again.');
        }

        if (!$response->successful()) {
            Log::warning('PayMongo checkout failed for order '.$order->id, [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect()
                ->route('payment.checkout', $order)
                ->with('error', 'PayMongo error: '.$response->body());
        }

        $checkoutSessionId = data_get($response->json(), 'data.id');
        $checkoutUrl = data_get($response->json(), 'data.attributes.checkout_url');

        if (!$checkoutUrl) {
            return redirect()
                ->route('payment.checkout', $order)
                ->with('error', 'No checkout_url returned by PayMongo.');
        }

        $order->forceFill([
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => $paymentMethod,
            'paymongo_checkout_session_id' => $checkoutSessionId,
            'payment_reference' => $reference,
        ])->save();

        session()->put('payment_order_id', $order->id);

        return redirect()->away($checkoutUrl);
    }

    public function success(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($request, $order);

        if ($order->payment_status === Order::PAYMENT_PENDING && $order->status === 'Pending') {
            $order->forceFill(['status' => 'For Verification'])->save();
        }

        session()->forget('buy_now');
        session()->forget('cart');
        session()->forget('payment_order_id');

        return view('payment.success', [
            'order' => $order->fresh(),
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorizeCustomerOrder($request, $order);

        if ($order->payment_status !== Order::PAYMENT_PAID) {
            $order->forceFill([
                'payment_status' => Order::PAYMENT_CANCELLED,
            ])->save();
        }

        return view('payment.cancel', [
            'order' => $order->fresh(),
        ]);
    }

    public function webhook(Request $request)
    {
        if (!$this->paymongoSignatureIsValid($request)) {
            abort(403, 'Invalid PayMongo signature.');
        }

        $payload = $request->json()->all();
        $eventType = (string) data_get($payload, 'data.attributes.type', '');
        $order = $this->findOrderFromPaymongoPayload($payload);

        if (!$order) {
            Log::info('PayMongo webhook ignored because no matching order was found.', [
                'event_type' => $eventType,
                'checkout_session_id' => data_get($payload, 'data.attributes.data.id'),
            ]);

            return response()->json(['message' => 'ignored']);
        }

        if (in_array($eventType, ['checkout_session.payment.paid', 'payment.paid', 'link.payment.paid'], true)) {
            $this->markOrderPaidFromPayload($order, $payload);
        } elseif ($eventType === 'payment.failed') {
            $order->forceFill([
                'payment_status' => Order::PAYMENT_FAILED,
            ])->save();
        }

        return response()->json(['message' => 'received']);
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

    private function authorizeCustomerOrder(Request $request, Order $order): void
    {
        abort_unless($this->customerOwnsOrder($request, $order), 403);
    }

    private function customerOwnsOrder(Request $request, Order $order): bool
    {
        return $request->user() && (int) $order->user_id === (int) $request->user()->id;
    }

    private function itemsFromOrder(Order $order): array
    {
        return $order->items->map(function ($item) {
            return [
                'name' => $item->service_name ?? 'Item',
                'price' => (float) $item->unit_price,
                'qty' => (int) $item->quantity,
                'variation_label' => $item->variation_label,
                'subtotal' => (float) $item->subtotal,
            ];
        })->values()->all();
    }

    private function lineItemsFromOrder(Order $order): array
    {
        return collect($this->itemsFromOrder($order))->map(function (array $item) {
            return [
                'name' => $item['name'] ?? 'Item',
                'quantity' => max(1, (int) ($item['qty'] ?? 1)),
                'amount' => (int) round(((float) ($item['price'] ?? 0)) * 100),
                'currency' => 'PHP',
                'description' => $item['variation_label'] ?? null,
            ];
        })->values()->all();
    }

    private function paymongoOrderReference(Order $order): string
    {
        return 'ORDER-'.$order->id;
    }

    private function paymongoSecretKey(): ?string
    {
        $secretKey = trim((string) config('services.paymongo.secret_key', ''));

        return $secretKey !== '' ? $secretKey : null;
    }

    private function paymongoSignatureIsValid(Request $request): bool
    {
        $secret = config('services.paymongo.webhook_secret');

        if (!$secret) {
            return !app()->environment('production');
        }

        $signatureHeader = (string) $request->header('Paymongo-Signature', '');

        if ($signatureHeader === '') {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $piece) {
            [$key, $value] = array_pad(explode('=', trim($piece), 2), 2, '');

            if ($key !== '') {
                $parts[$key] = $value;
            }
        }

        $timestamp = $parts['t'] ?? null;

        if (!$timestamp) {
            return hash_equals(hash_hmac('sha256', $request->getContent(), $secret), $signatureHeader);
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$request->getContent(), $secret);

        foreach (array_filter([$parts['te'] ?? null, $parts['li'] ?? null]) as $provided) {
            if (hash_equals($expected, $provided)) {
                return true;
            }
        }

        return false;
    }

    private function findOrderFromPaymongoPayload(array $payload): ?Order
    {
        $resourceId = (string) data_get($payload, 'data.attributes.data.id', '');

        if (str_starts_with($resourceId, 'cs_')) {
            $order = Order::query()
                ->where('paymongo_checkout_session_id', $resourceId)
                ->first();

            if ($order) {
                return $order;
            }
        }

        foreach ($this->orderReferenceCandidates($payload) as $candidate) {
            $orderId = $this->orderIdFromReference($candidate);

            if ($orderId) {
                return Order::query()->find($orderId);
            }
        }

        return null;
    }

    private function orderReferenceCandidates(array $payload): array
    {
        return array_filter([
            data_get($payload, 'data.attributes.data.attributes.metadata.order_id'),
            data_get($payload, 'data.attributes.data.attributes.metadata.pm_reference_number'),
            data_get($payload, 'data.attributes.data.attributes.reference_number'),
            data_get($payload, 'data.attributes.data.attributes.external_reference_number'),
            data_get($payload, 'data.attributes.data.attributes.payments.0.attributes.metadata.order_id'),
            data_get($payload, 'data.attributes.data.attributes.payments.0.attributes.metadata.pm_reference_number'),
            data_get($payload, 'data.attributes.data.attributes.payments.0.attributes.external_reference_number'),
            data_get($payload, 'data.attributes.data.attributes.description'),
        ]);
    }

    private function orderIdFromReference(mixed $reference): ?int
    {
        if ($reference === null || $reference === '') {
            return null;
        }

        $reference = (string) $reference;

        if (ctype_digit($reference)) {
            return (int) $reference;
        }

        if (preg_match('/(?:ORDER-|Order #)(\d+)/i', $reference, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function markOrderPaidFromPayload(Order $order, array $payload): void
    {
        $paidAt = data_get($payload, 'data.attributes.data.attributes.paid_at')
            ?? data_get($payload, 'data.attributes.data.attributes.payments.0.attributes.paid_at')
            ?? now();

        if (is_numeric($paidAt)) {
            $paidAt = Carbon::createFromTimestamp((int) $paidAt);
        }

        $paymentReference = data_get($payload, 'data.attributes.data.attributes.payments.0.id')
            ?? data_get($payload, 'data.attributes.data.id')
            ?? $order->payment_reference;

        $order->forceFill([
            'payment_status' => Order::PAYMENT_PAID,
            'payment_reference' => $paymentReference,
            'paid_at' => $paidAt,
            'status' => $order->status === 'Pending' ? 'For Verification' : $order->status,
        ])->save();
    }

    private function getCheckoutItems(): array
    {
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

    private function normalizeItemsFromCartStructure(array $items): array
    {
        return collect($items)->values()->map(function ($item) {
            return [
                'name' => $item['name'] ?? 'Item',
                'price' => (float) ($item['price'] ?? 0),
                'qty' => (int) ($item['qty'] ?? 1),
            ];
        })->values()->all();
    }
}
