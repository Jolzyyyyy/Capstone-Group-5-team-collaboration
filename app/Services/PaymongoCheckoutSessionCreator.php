<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymongoCheckoutSessionCreator
{
    /**
     * @return array{checkout_url: string}
     */
    public function create(Order $order, string $paymentMethod): array
    {
        $order->load('items');

        $secretKey = $this->secretKey();
        if (!$secretKey) {
            throw new PaymongoCheckoutException('PAYMONGO_SECRET_KEY is missing in .env. The order was saved, but online payment cannot start yet.');
        }

        $reference = $this->orderReference($order);
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
                    'amount' => (int) round(((float) $order->total_price) * 100),
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

            throw new PaymongoCheckoutException('PayMongo did not respond in time. Please try again.');
        }

        if (!$response->successful()) {
            Log::warning('PayMongo checkout failed for order '.$order->id, [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new PaymongoCheckoutException('PayMongo error: '.$response->body());
        }

        $checkoutSessionId = data_get($response->json(), 'data.id');
        $checkoutUrl = data_get($response->json(), 'data.attributes.checkout_url');

        if (!$checkoutUrl) {
            throw new PaymongoCheckoutException('No checkout_url returned by PayMongo.');
        }

        $order->forceFill([
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => $paymentMethod,
            'paymongo_checkout_session_id' => $checkoutSessionId,
            'payment_reference' => $reference,
        ])->save();

        return ['checkout_url' => $checkoutUrl];
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

    private function orderReference(Order $order): string
    {
        return 'ORDER-'.$order->id;
    }

    private function secretKey(): ?string
    {
        $secretKey = trim((string) config('services.paymongo.secret_key', ''));

        return $secretKey !== '' ? $secretKey : null;
    }
}
