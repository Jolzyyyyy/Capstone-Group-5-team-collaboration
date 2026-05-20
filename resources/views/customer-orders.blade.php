<x-app-layout>
    <!-- 1. STYLE BLOCK -->
    <style>
        .main-wrapper {
            padding: 2rem;
            animation: fadeIn 0.4s ease-in-out;
            max-width: 1200px;
            margin: 0 auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-end; /* Inayos para pantay sa text */
            margin-bottom: 2rem;
        }

        .order-card-container {
            background: white;
            border-radius: 20px;
            border: 1px solid #F1F5F9;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        /* Dark Mode Support */
        .dark-mode-active .order-card-container {
            background: #1F2937;
            border-color: #374151;
        }

        table { width: 100%; border-collapse: collapse; }

        th {
            background: #F8FAFC;
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark-mode-active th { background: #111827; color: #94A3B8; }

        td {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #F1F5F9;
            font-size: 14px;
            color: #1E293B;
        }

        .dark-mode-active td { border-color: #374151; color: #E5E7EB; }

        .status-pill {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-completed { background: #DCFCE7; color: #166534; }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-processing { background: #E0F2FE; color: #0369A1; }
        .status-cancelled { background: #FEE2E2; color: #991B1B; }

        .btn-view {
            background: #F1F5F9;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 11px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-view:hover {
            background: #4F46E5;
            color: white;
        }

        /* Fixed SVG Size for Empty State */
        .empty-icon {
            width: 80px !important;
            height: 80px !important;
            margin: 0 auto;
            color: #CBD5E1;
        }
    </style>

    <!-- 2. CONTENT BLOCK -->
    <div class="main-wrapper">
        <div class="header-flex">
            <div>
                <h1 class="text-2xl font-black tracking-tight" :class="darkMode ? 'text-white' : 'text-slate-900'">My Orders</h1>
                <p class="text-slate-500 font-medium text-sm">Track and manage your printing requests</p>
            </div>
            <a href="{{ route('services.index') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 transition-all shadow-md">
                + New Order
            </a>
        </div>

        <div class="order-card-container">
            @if(isset($orders) && $orders->count() > 0)
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Order Ref</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="font-bold">
                                    <span class="text-slate-400">#</span>ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    <span class="status-pill 
                                        @if($order->status == 'completed') status-completed 
                                        @elseif($order->status == 'pending') status-pending
                                        @elseif($order->status == 'processing') status-processing
                                        @else status-cancelled @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="font-black text-slate-900">
                                    ₱{{ number_format($order->total_price ?? $order->amount, 2) }}
                                </td>
                                <td class="text-slate-500 text-sm">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('my-orders.show', $order->id) }}" class="btn-view text-uppercase">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- CLEAN EMPTY STATE -->
                <div class="py-20 text-center">
                    <svg class="empty-icon mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-slate-900" :class="darkMode ? 'text-white' : ''">No orders found</h3>
                    <p class="text-slate-500 text-sm mb-6">Looks like you haven't placed any orders yet.</p>
                    <a href="{{ route('services.index') }}" class="text-indigo-600 font-bold text-sm hover:underline">
                        Browse Services &rarr;
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>