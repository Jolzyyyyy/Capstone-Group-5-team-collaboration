<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
            ->visibleToPortalUser(request()->user())
            ->with(['user', 'adminClient'])
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizePortalOrder($order);

        $order->load(['items.service', 'user', 'adminClient', 'files']);

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

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $this->authorizePortalOrder($order);
        abort_unless(request()->user()->isDeveloper(), 403);

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    private function authorizePortalOrder(Order $order): void
    {
        $user = request()->user();

        abort_unless($user && $order->loadMissing('user')->isVisibleToPortalUser($user), 403);
    }
}
