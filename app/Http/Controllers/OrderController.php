<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | USER PAGES (My Orders)
    |--------------------------------------------------------------------------
    */

    public function myOrders()
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.my_index', compact('orders'));
    }

    public function myShow(Order $order)
    {
        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // ✅ Load items + service + files (ZIP)
        $order->load(['items.service', 'files']);

        // ✅ Reuse orders.show
        return view('orders.show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN PAGES (Manage Orders)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::query()
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.service', 'user', 'files']);

        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'max:255'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
