<?php

namespace App\Services;

class CheckoutCartSummary
{
    /**
     * @return array{cart: array<int, array<string, mixed>>, summary: array{items_count: int, total: float}, paymongoConfigured: bool}
     */
    public function forCart(array $rawCart): array
    {
        $cart = [];
        $itemsCount = 0;
        $total = 0.0;

        foreach ($rawCart as $cartKey => $row) {
            $qty = (int) ($row['qty'] ?? 1);
            $unitPrice = (float) ($row['price'] ?? 0);
            $subtotal = $qty * $unitPrice;

            $itemsCount += $qty;
            $total += $subtotal;

            $cart[] = [
                'cart_key' => $cartKey,
                'service_id' => (int) ($row['service_id'] ?? 0),
                'variation_id' => (int) ($row['variation_id'] ?? 0),
                'service_item_id' => $row['service_item_id'] ?? '',
                'name' => $row['name'] ?? 'Service',
                'category' => $row['category'] ?? '',
                'variation_label' => $row['variation_label'] ?? '',
                'unit' => $row['unit'] ?? '',
                'price_type' => $row['price_type'] ?? 'retail',
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'subtotal' => $subtotal,
                'attached_file' => $row['attached_file'] ?? null,
            ];
        }

        return [
            'cart' => $cart,
            'summary' => [
                'items_count' => $itemsCount,
                'total' => $total,
            ],
            'paymongoConfigured' => $this->paymongoSecretKey() !== null,
        ];
    }

    public function hasMissingPrintFiles(array $cart): bool
    {
        foreach ($cart as $row) {
            $file = $row['attached_file'] ?? null;

            if (!is_array($file) || empty($file['path'])) {
                return true;
            }
        }

        return false;
    }

    public function paymongoIsConfigured(): bool
    {
        return $this->paymongoSecretKey() !== null;
    }

    private function paymongoSecretKey(): ?string
    {
        $secretKey = trim((string) config('services.paymongo.secret_key', ''));

        return $secretKey !== '' ? $secretKey : null;
    }
}
