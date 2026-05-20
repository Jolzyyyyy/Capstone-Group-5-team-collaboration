<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | USER PAGES (My Orders - Customer Side)
    |--------------------------------------------------------------------------
    */

    public function myOrders()
    {
        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        // Ito ang gagamitin nating file para sa Customer
        return view('customer-orders', compact('orders'));
    }

    /**
     * Show a specific order that belongs to the logged-in user.
     */
    public function myShow(Order $order)
    {
        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.service', 'files']);
        return view('orders.show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN PAGES (Manage Orders - Admin Side)
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::query()
            ->visibleToPortalUser(auth()->user())
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless(Order::query()
            ->visibleToPortalUser(auth()->user())
            ->whereKey($order->getKey())
            ->exists(), 403);

        $order->load(['items.service', 'user', 'files']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorizePortalOrder($order);

        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorizePortalOrder($order);

        $validated = $request->validate([
            'status' => ['required', 'string', 'max:255'],
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->authorizePortalOrder($order);
        abort_unless(request()->user()->isDeveloper(), 403);

        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    private function authorizePortalOrder(Order $order): void
    {
        $user = request()->user();

        abort_unless($user && $order->loadMissing('user')->isVisibleToPortalUser($user), 403);
    }
}
