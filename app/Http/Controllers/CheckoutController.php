<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CheckoutOrderPlacer;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private CheckoutOrderPlacer $checkoutOrderPlacer)
    {
    }

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

        $order = $this->checkoutOrderPlacer->place($request, $rawCart, $validated);

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

}
