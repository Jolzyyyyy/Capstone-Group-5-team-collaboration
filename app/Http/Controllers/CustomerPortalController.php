<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $orderQuery = $user->orders();
        $orders = (clone $orderQuery)->latest()->get();

        return view('dashboard', [
            'orders' => $orders,
            'recentOrders' => $orders->take(5),
            'activeOrders' => $orders->reject(fn ($order) => in_array(strtolower((string) $order->status), ['completed', 'delivered', 'cancelled', 'canceled'], true)),
            'readyOrders' => $orders->filter(fn ($order) => in_array(strtolower((string) $order->status), ['ready', 'ready_for_pickup', 'shipped', 'out_for_delivery'], true)),
            'notifications' => collect(),
            'paymentMethodsCount' => 0,
            'assignedAdminClient' => $user->assignedAdminClient,
            'availableServices' => Service::where('is_active', true)->count(),
            'orderCount' => $orders->count(),
            'activeOrderCount' => $orders->filter(fn ($order) => in_array(strtolower((string) $order->status), ['pending', 'for verification', 'processing', 'ready'], true))->count(),
            'completedOrderCount' => $orders->filter(fn ($order) => strtolower((string) $order->status) === 'completed')->count(),
            'totalSpent' => (float) $orders->sum('total_price'),
        ]);
    }

    public function notifications(Request $request): View
    {
        return view('notifications');
    }

    public function security(Request $request): View
    {
        return view('security');
    }

    public function settings(Request $request): View
    {
        return view('settings');
    }

    public function help(Request $request): View
    {
        return view('help-center');
    }
}
