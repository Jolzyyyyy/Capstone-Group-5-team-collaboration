<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * ADMIN: manage all orders (index/show/edit/update/destroy)
     * USER: can only access myOrders + myShow
     *
     * NOTE:
     * We enforce access using ROUTES middleware (role groups).
     * Here we still require auth for all methods.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | USER PAGES (My Orders)
    |--------------------------------------------------------------------------
    */

    /**
     * Show logged-in user's orders only.
     * GET /my-orders
     */
    public function myOrders()
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.my_index', compact('orders'));
    }

    /**
     * Show a specific order that belongs to the logged-in user.
     * GET /my-orders/{order}
     */
    public function myShow(Order $order)
    {
        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.service']);

        return view('orders.my_show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN PAGES (Manage Orders)
    |--------------------------------------------------------------------------
    */

    /**
     * Display a listing of all orders (admin).
     * GET /orders
     */
    public function index()
    {
        $orders = Order::query()
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a specific order (admin).
     * GET /orders/{order}
     */
    public function show(Order $order)
    {
        $order->load(['items.service', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Edit order (admin).
     * GET /orders/{order}/edit
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update order (admin).
     * PUT /orders/{order}
     */
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

    /**
     * Delete order (admin).
     * DELETE /orders/{order}
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
