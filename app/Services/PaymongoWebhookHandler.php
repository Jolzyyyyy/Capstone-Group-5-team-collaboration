<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PaymongoWebhookHandler
{
    public function signatureIsValid(Request $request): bool
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

    public function handle(array $payload): string
    {
        $eventType = (string) data_get($payload, 'data.attributes.type', '');
        $order = $this->findOrderFromPayload($payload);

        if (!$order) {
            Log::info('PayMongo webhook ignored because no matching order was found.', [
                'event_type' => $eventType,
                'checkout_session_id' => data_get($payload, 'data.attributes.data.id'),
            ]);

            return 'ignored';
        }

        if (in_array($eventType, ['checkout_session.payment.paid', 'payment.paid', 'link.payment.paid'], true)) {
            $this->markOrderPaidFromPayload($order, $payload);
        } elseif ($eventType === 'payment.failed') {
            $order->forceFill([
                'payment_status' => Order::PAYMENT_FAILED,
            ])->save();
        }

        return 'received';
    }

    private function findOrderFromPayload(array $payload): ?Order
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
}
