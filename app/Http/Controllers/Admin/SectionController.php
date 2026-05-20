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

class SectionController extends Controller
{
    public function customers(Request $request): View
    {
        $user = $request->user();
        $customers = $this->customerQuery($user)
            ->with('assignedAdminClient')
            ->latest()
            ->limit(12)
            ->get();

        return view('Admin.sections.index', [
            'title' => 'Customers',
            'kicker' => $user->canViewAllPortalRecords() ? 'Customer Database' : 'Assigned Customer Database',
            'description' => $user->canViewAllPortalRecords()
                ? 'All registered customer accounts and their assigned admin-client coverage.'
                : 'Customer accounts assigned to this admin-client role.',
            'cards' => $this->summaryCards($user),
            'rows' => $customers->map(fn (User $customer) => [
                'title' => $customer->name,
                'meta' => $customer->email,
                'note' => $user->canViewAllPortalRecords()
                    ? 'Admin client: ' . ($customer->assignedAdminClient?->name ?? 'Unassigned')
                    : 'Assigned customer',
            ]),
            'emptyMessage' => 'No customers are available for this role scope.',
        ]);
    }

    public function orders(Request $request): View
    {
        $user = $request->user();
        $orders = $this->orderQuery($user)
            ->with(['user', 'adminClient'])
            ->latest()
            ->limit(12)
            ->get();

        return view('Admin.sections.index', [
            'title' => 'Orders',
            'kicker' => 'Order And Delivery Monitoring',
            'description' => 'Role-scoped order records, customer context, delivery readiness, and current production status.',
            'cards' => $this->summaryCards($user),
            'rows' => $orders->map(fn (Order $order) => [
                'title' => 'Order #' . $order->id . ' - ' . $order->status,
                'meta' => ($order->customer_name ?? $order->user?->name ?? 'No customer') . ' / ' . ($order->customer_email ?? $order->user?->email ?? 'No email'),
                'note' => 'PHP ' . number_format((float) $order->total_price, 2) . ' / ' . optional($order->created_at)->format('M d, Y h:i A'),
            ]),
            'emptyMessage' => 'No order records are available yet.',
        ]);
    }

    public function services(Request $request): View
    {
        $user = $request->user();
        $services = Service::query()
            ->withCount(['variations', 'activeVariations'])
            ->orderBy('category')
            ->orderBy('name')
            ->limit(12)
            ->get();

        return view('Admin.sections.index', [
            'title' => 'Services',
            'kicker' => 'Service Catalog',
            'description' => 'Service records customers can browse and staff can connect to orders.',
            'cards' => $this->summaryCards($user),
            'rows' => $services->map(fn (Service $service) => [
                'title' => $service->name,
                'meta' => ($service->category ?? 'Uncategorized') . ' / ' . ($service->is_active ? 'Active' : 'Inactive'),
                'note' => ($service->active_variations_count ?? 0) . ' active variations / ' . ($service->variations_count ?? 0) . ' total variations',
            ]),
            'emptyMessage' => 'No service records are available yet.',
        ]);
    }

    public function analytics(Request $request): View
    {
        $user = $request->user();
        $orders = $this->orderQuery($user);
        $statusCounts = (clone $orders)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('Admin.sections.index', [
            'title' => 'Analytics',
            'kicker' => 'Sales And Operations',
            'description' => 'Role-scoped sales value, production status, and account coverage.',
            'cards' => $this->summaryCards($user),
            'rows' => collect(['Pending', 'For Verification', 'Processing', 'Ready', 'Completed', 'Cancelled'])->map(fn (string $status) => [
                'title' => $status,
                'meta' => (int) ($statusCounts[$status] ?? 0) . ' orders',
                'note' => 'Total value: PHP ' . number_format((float) (clone $orders)->where('status', $status)->sum('total_price'), 2),
            ]),
            'emptyMessage' => 'No analytics records yet.',
        ]);
    }

    public function reports(Request $request): View
    {
        $user = $request->user();
        $orders = $this->orderQuery($user);
        $recentOrders = (clone $orders)->with(['user', 'adminClient'])->latest()->limit(12)->get();

        return view('Admin.sections.index', [
            'title' => 'Reports',
            'kicker' => 'Operational Reports',
            'description' => 'Recent order records prepared for monitoring sales, delivery readiness, and customer follow-up.',
            'cards' => $this->summaryCards($user),
            'rows' => $recentOrders->map(fn (Order $order) => [
                'title' => 'Order #' . $order->id . ' - ' . $order->status,
                'meta' => ($order->customer_email ?? $order->user?->email ?? 'No email') . ' / PHP ' . number_format((float) $order->total_price, 2),
                'note' => $user->canViewAllPortalRecords()
                    ? 'Admin client: ' . ($order->adminClient?->name ?? 'Unassigned')
                    : 'Assigned order record',
            ]),
            'emptyMessage' => 'No report records are available yet.',
        ]);
    }

    public function settings(Request $request): View
    {
        $user = $request->user();

        return view('Admin.sections.index', [
            'title' => 'Settings',
            'kicker' => 'Portal Settings',
            'description' => 'Role scope, account status, and security controls for the staff workspace.',
            'cards' => $this->summaryCards($user),
            'rows' => collect([
                ['title' => 'Portal role', 'meta' => str_replace('_', ' ', $user->role), 'note' => $user->isDeveloper() ? 'Developer oversight enabled' : ($user->isAdmin() ? 'Admin operations enabled' : 'Admin-client scoped access')],
                ['title' => 'Email OTP', 'meta' => session('staff_otp_passed') ? 'Verified for this session' : 'Required', 'note' => 'Portal sessions require email verification.'],
                ['title' => 'Reference profile', 'meta' => $user->isAdminClient() ? ($user->hasCompletedAdminClientProfile() ? 'Complete' : 'Incomplete') : ucfirst($user->role) . ' account', 'note' => $user->isAdminClient() ? 'Used for future system reference.' : 'Staff account managed separately from customer accounts.'],
            ]),
            'emptyMessage' => 'No settings records yet.',
        ]);
    }

    public function help(Request $request): View
    {
        $user = $request->user();
        $recentAuditLogs = AuditLog::query()
            ->with(['actor', 'targetUser'])
            ->when(!$user->canViewAllPortalRecords(), function (Builder $query) use ($user) {
                $query->where(function (Builder $scope) use ($user) {
                    $scope->where('actor_id', $user->id)
                        ->orWhere('target_user_id', $user->id);
                });
            })
            ->latest()
            ->limit(8)
            ->get();

        return view('Admin.sections.index', [
            'title' => 'Help Center',
            'kicker' => 'Portal Reference',
            'description' => 'Role-based access reminders and recent audit context for support work.',
            'cards' => $this->summaryCards($user),
            'rows' => $recentAuditLogs->map(fn (AuditLog $log) => [
                'title' => str_replace('_', ' ', ucfirst($log->action)),
                'meta' => optional($log->actor)->email ?? 'System / invite link',
                'note' => $log->targetUser ? 'Target: ' . $log->targetUser->email : 'No target account',
            ]),
            'emptyMessage' => 'No audit support records yet.',
        ]);
    }

    private function summaryCards(User $user): array
    {
        $orders = $this->orderQuery($user);
        $customers = $this->customerQuery($user);

        $cards = [
            ['label' => 'Sales', 'value' => 'PHP ' . number_format((float) (clone $orders)->sum('total_price'), 2), 'note' => 'Role-scoped order value'],
            ['label' => 'Active Orders', 'value' => (clone $orders)->whereIn('status', ['Pending', 'For Verification', 'Processing', 'Ready'])->count(), 'note' => 'Pending through ready'],
            ['label' => $user->canViewAllPortalRecords() ? 'Customers' : 'Assigned Customers', 'value' => (clone $customers)->count(), 'note' => 'Visible customer accounts'],
            ['label' => 'Services', 'value' => Service::where('is_active', true)->count(), 'note' => 'Active catalog records'],
        ];

        if ($user->isDeveloper()) {
            $cards[] = [
                'label' => 'Admin Accounts',
                'value' => User::where('role', User::ROLE_ADMIN)->whereNotNull('preregistered_by')->whereNotNull('approved_at')->count(),
                'note' => User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_ADMIN_CLIENT])
                    ->whereNotNull('preregistered_by')
                    ->whereNull('approved_at')
                    ->count() . ' pending approval',
            ];
        }

        return $cards;
    }

    private function orderQuery(User $user): Builder
    {
        return Order::query()->visibleToPortalUser($user);
    }

    private function customerQuery(User $user): Builder
    {
        return User::query()
            ->where('role', User::ROLE_CUSTOMER)
            ->when(!$user->canViewAllPortalRecords(), fn (Builder $query) => $query->where('admin_client_id', $user->id));
    }
}
