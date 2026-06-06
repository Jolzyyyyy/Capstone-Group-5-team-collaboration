<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderFile;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutOrderPlacer
{
    public function place(Request $request, array $rawCart, array $validated): Order
    {
        return DB::transaction(function () use ($request, $rawCart, $validated): Order {
            $customer = $request->user();

            $order = Order::create([
                'user_id' => auth()->id(),
                'admin_client_id' => $customer?->admin_client_id,
                'customer_name' => trim($validated['customer_name']),
                'customer_email' => filled($validated['customer_email'] ?? null) ? strtolower(trim($validated['customer_email'])) : null,
                'customer_phone' => filled($validated['customer_phone'] ?? null) ? trim($validated['customer_phone']) : null,
                'fulfillment_method' => $validated['fulfillment_method'],
                'delivery_address' => filled($validated['delivery_address'] ?? null) ? trim($validated['delivery_address']) : null,
                'customer_note' => filled($validated['customer_note'] ?? null) ? trim($validated['customer_note']) : null,
                'status' => 'Pending',
                'payment_status' => Order::PAYMENT_UNPAID,
                'total_price' => 0,
            ]);

            foreach ($rawCart as $row) {
                [$service, $variation] = $this->resolveReviewedCartCatalog($row);

                $qty = (int) ($row['qty'] ?? 1);
                $unitPrice = (float) ($row['price'] ?? 0);
                $subtotal = $qty * $unitPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $service->id,
                    'service_variation_id' => $variation?->id,
                    'service_item_id' => $row['service_item_id'] ?? $variation?->service_item_id,
                    'service_name' => $row['name'] ?? $service->name,
                    'variation_label' => $row['variation_label'] ?? $variation?->variation_label,
                    'price_type' => $row['price_type'] ?? 'retail',
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ]);

                $file = $row['attached_file'] ?? null;

                if ($file) {
                    OrderFile::create([
                        'order_id' => $order->id,
                        'original_name' => $file['original_name'] ?? 'Attached file',
                        'path' => $file['path'],
                        'mime' => $file['mime'] ?? null,
                        'size' => $file['size'] ?? null,
                    ]);
                }
            }

            $order->recomputeTotal();

            session()->forget('cart');
            session()->put('payment_order_id', $order->id);

            return $order;
        });
    }

    private function resolveReviewedCartCatalog(array $row): array
    {
        $service = !empty($row['service_id'])
            ? Service::query()->find((int) $row['service_id'])
            : null;

        $variation = null;

        if ($service && !empty($row['variation_id'])) {
            $variation = ServiceVariation::query()
                ->where('id', (int) $row['variation_id'])
                ->where('service_id', $service->id)
                ->first();
        }

        if ($service) {
            return [$service, $variation];
        }

        $fallbackService = Service::query()->firstOrCreate(
            [
                'name' => 'Reviewed Checkout Item',
                'category' => 'Manual Checkout',
            ],
            [
                'retail_price' => 0,
                'bulk_price' => 0,
                'unit' => 'item',
                'description' => 'Internal placeholder for reviewed homepage cart items that are saved from checkout.',
                'is_active' => false,
            ]
        );

        return [$fallbackService, null];
    }
}
