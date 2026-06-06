<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if ($this->hasMissingPrintFiles($rawCart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Please attach a print-ready file to every service before checkout.');
        }

        $cart = [];
        $itemsCount = 0;
        $total = 0;

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

        $summary = [
            'items_count' => $itemsCount,
            'total' => $total,
        ];
        $paymongoConfigured = $this->paymongoSecretKey() !== null;

        return view('checkout.index', compact('cart', 'summary', 'paymongoConfigured'));
    }

    public function place(Request $request)
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if ($this->hasMissingPrintFiles($rawCart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Please attach a print-ready file to every service before checkout.');
        }

        $request->merge([
            'fulfillment_method' => $request->input('fulfillment_method', 'pickup'),
            'payment_method' => $request->input('payment_method', 'gcash'),
        ]);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'fulfillment_method' => ['required', 'in:pickup,delivery'],
            'delivery_address' => ['nullable', 'required_if:fulfillment_method,delivery', 'string', 'max:2000'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:gcash,card,grab_pay,paymaya'],
            'print_file_confirmed' => ['accepted'],
        ], [
            'print_file_confirmed.accepted' => 'Please confirm that the attached file is the final file to be printed.',
        ]);

        if (!$this->paymongoSecretKey()) {
            return back()
                ->withInput()
                ->with('error', 'Online payment is not configured yet. Add PAYMONGO_SECRET_KEY in .env before placing PayMongo orders.');
        }

        $order = DB::transaction(function () use ($request, $rawCart, $validated) {
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

        return app(PaymongoCheckoutController::class)
            ->startPayment($request, $order, $validated['payment_method']);
    }

    public function paymongoIndex(Request $request)
    {
        return app(PaymongoCheckoutController::class)->checkout($request);
    }

    private function hasMissingPrintFiles(array $cart): bool
    {
        foreach ($cart as $row) {
            $file = $row['attached_file'] ?? null;

            if (!is_array($file) || empty($file['path'])) {
                return true;
            }
        }

        return false;
    }

    private function paymongoSecretKey(): ?string
    {
        $secretKey = trim((string) config('services.paymongo.secret_key', ''));

        return $secretKey !== '' ? $secretKey : null;
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
