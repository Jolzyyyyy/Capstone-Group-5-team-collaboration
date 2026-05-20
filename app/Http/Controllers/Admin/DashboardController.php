<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $isDeveloper = $user->isDeveloper();
        $isAdminClient = $user->isAdminClient();

        $orderQuery = Order::query()->visibleToPortalUser($user);
        $customerQuery = User::query()
            ->where('role', User::ROLE_CUSTOMER)
            ->when($isAdminClient, fn (Builder $query) => $query->where('admin_client_id', $user->id));

        $statusCounts = (clone $orderQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pipeline = collect([
            ['status' => 'Pending', 'label' => 'New Orders', 'note' => 'Needs staff review'],
            ['status' => 'For Verification', 'label' => 'For Verification', 'note' => 'Files or payment to check'],
            ['status' => 'Processing', 'label' => 'In Production', 'note' => 'Printing or preparation'],
            ['status' => 'Ready', 'label' => 'Ready / Delivery', 'note' => 'Ready for pickup or release'],
            ['status' => 'Completed', 'label' => 'Completed', 'note' => 'Finished requests'],
            ['status' => 'Cancelled', 'label' => 'Cancelled', 'note' => 'Stopped requests'],
        ])->map(fn (array $item) => [
            ...$item,
            'count' => (int) ($statusCounts[$item['status']] ?? 0),
        ]);

        $stats = [
            'orders' => (clone $orderQuery)->count(),
            'orders_this_month' => (clone $orderQuery)->where('created_at', '>=', now()->startOfMonth())->count(),
            'active_orders' => (clone $orderQuery)->whereIn('status', ['Pending', 'For Verification', 'Processing', 'Ready'])->count(),
            'completed_orders' => (clone $orderQuery)->where('status', 'Completed')->count(),
            'sales_total' => (float) (clone $orderQuery)->sum('total_price'),
            'sales_this_month' => (float) (clone $orderQuery)->where('created_at', '>=', now()->startOfMonth())->sum('total_price'),
            'customers' => (clone $customerQuery)->count(),
            'services' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'inactive_services' => Service::where('is_active', false)->count(),
            'pending_admin_clients' => $isDeveloper
                ? $this->developerManagedAdminQuery()->whereNull('approved_at')->count()
                : 0,
            'approved_admin_clients' => $isDeveloper
                ? User::where('role', User::ROLE_ADMIN)->whereNotNull('preregistered_by')->whereNotNull('approved_at')->count()
                : 0,
            'unassigned_orders' => $isDeveloper
                ? Order::whereNull('admin_client_id')->count()
                : 0,
            'unassigned_customers' => $isDeveloper
                ? User::where('role', User::ROLE_CUSTOMER)->whereNull('admin_client_id')->count()
                : 0,
        ];

        $recentOrders = (clone $orderQuery)
            ->with(['user', 'adminClient'])
            ->latest()
            ->limit(6)
            ->get();

        $recentCustomers = (clone $customerQuery)
            ->with('assignedAdminClient')
            ->latest()
            ->limit(5)
            ->get();

        $auditQuery = AuditLog::query()
            ->with(['actor', 'targetUser'])
            ->when(!$isDeveloper, function (Builder $query) use ($user) {
                $query->where(function (Builder $scope) use ($user) {
                    $scope->where('actor_id', $user->id)
                        ->orWhere('target_user_id', $user->id);
                });
            });

        $recentAuditLogs = $auditQuery
            ->latest()
            ->limit($isDeveloper ? 8 : 5)
            ->get();

        $recentAdminClients = collect();

        if ($isDeveloper) {
            $recentAdminClients = $this->developerManagedAdminQuery()
                ->with('adminClientProfile')
                ->withCount(['assignedCustomers', 'managedOrders'])
                ->latest('updated_at')
                ->limit(5)
                ->get();
        }

        return view('Admin.dashboard', [
            'section' => $isDeveloper
                ? 'developer-dashboard'
                : ($isAdminClient ? 'admin-client-dashboard' : 'dashboard'),
            'stats' => $stats,
            'pipeline' => $pipeline,
            'recentOrders' => $recentOrders,
            'recentCustomers' => $recentCustomers,
            'recentAuditLogs' => $recentAuditLogs,
            'recentAdminClients' => $recentAdminClients,
            'profile' => $isAdminClient ? $user->adminClientProfile : null,
        ]);
    }

    private function developerManagedAdminQuery(): Builder
    {
        return User::query()
            ->where(function (Builder $query) {
                $query->where('role', User::ROLE_ADMIN_CLIENT)
                    ->orWhere(function (Builder $adminQuery) {
                        $adminQuery->where('role', User::ROLE_ADMIN)
                            ->whereNotNull('preregistered_by');
                    });
            });
    }
}
