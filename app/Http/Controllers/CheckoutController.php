<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CheckoutCartSummary;
use App\Services\CheckoutOrderPlacer;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutOrderPlacer $checkoutOrderPlacer,
        private CheckoutCartSummary $checkoutCartSummary
    ) {
    }

    public function index()
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if ($this->checkoutCartSummary->hasMissingPrintFiles($rawCart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Please attach a print-ready file to every service before checkout.');
        }

        [
            'cart' => $cart,
            'summary' => $summary,
            'paymongoConfigured' => $paymongoConfigured,
        ] = $this->checkoutCartSummary->forCart($rawCart);

        return view('checkout.index', compact('cart', 'summary', 'paymongoConfigured'));
    }

    public function place(Request $request)
    {
        $rawCart = session()->get('cart', []);

        if (empty($rawCart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        if ($this->checkoutCartSummary->hasMissingPrintFiles($rawCart)) {
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

        if (!$this->checkoutCartSummary->paymongoIsConfigured()) {
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

}
