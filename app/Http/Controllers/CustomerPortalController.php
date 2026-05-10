<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    public function notifications(Request $request): View
    {
        $user = $request->user();
        $recentOrders = $user->orders()->latest()->limit(8)->get();

        return view('customer.sections.index', [
            'title' => 'Notifications',
            'kicker' => 'Customer Updates',
            'description' => 'Recent order movement and account notices tied to your customer profile.',
            'cards' => [
                ['label' => 'Active Orders', 'value' => $user->orders()->whereIn('status', ['Pending', 'For Verification', 'Processing', 'Ready'])->count(), 'note' => 'Current print requests'],
                ['label' => 'Completed Orders', 'value' => $user->orders()->where('status', 'Completed')->count(), 'note' => 'Finished requests'],
                ['label' => 'Assigned Support', 'value' => $user->assignedAdminClient?->name ?? 'Pending', 'note' => $user->assignedAdminClient?->email ?? 'No assigned admin-client yet'],
            ],
            'rows' => $recentOrders->map(fn ($order) => [
                'title' => 'Order #' . $order->id . ' - ' . $order->status,
                'meta' => $order->created_at->format('M d, Y h:i A'),
                'note' => 'Recorded value: PHP ' . number_format((float) $order->total_price, 2),
            ]),
            'emptyMessage' => 'No order notifications yet.',
        ]);
    }

    public function security(Request $request): View
    {
        $user = $request->user();

        return view('customer.sections.index', [
            'title' => 'Security',
            'kicker' => 'Account Protection',
            'description' => 'Security status, sign-in method, and recovery contact information for your customer account.',
            'cards' => [
                ['label' => 'Email Verification', 'value' => $user->email_verified_at ? 'Verified' : 'Pending', 'note' => $user->email],
                ['label' => 'Google Login', 'value' => $user->hasGoogleLogin() ? 'Linked' : 'Not linked', 'note' => $user->hasGoogleLogin() ? 'Google sign-in is available' : 'Email/password sign-in only'],
                ['label' => 'Backup Email', 'value' => $user->backup_email ? 'Saved' : 'Missing', 'note' => $user->backup_email ?? 'Add one from your profile'],
            ],
            'rows' => collect([
                ['title' => 'Password status', 'meta' => $user->needsPasswordSetup() ? 'Manual password not set' : 'Manual password available', 'note' => $user->needsPasswordSetup() ? 'Set one from your profile page.' : 'You can update it from your profile page.'],
                ['title' => 'Session verification', 'meta' => session('customer_otp_passed') ? 'Passed' : 'Required when prompted', 'note' => 'OTP protects restricted customer pages.'],
            ]),
            'emptyMessage' => 'No security records yet.',
        ]);
    }

    public function settings(Request $request): View
    {
        $user = $request->user();

        return view('customer.sections.index', [
            'title' => 'Settings',
            'kicker' => 'Customer Preferences',
            'description' => 'Account details and service preferences used by your print request workspace.',
            'cards' => [
                ['label' => 'Profile', 'value' => $user->name, 'note' => $user->email],
                ['label' => 'Available Services', 'value' => Service::where('is_active', true)->count(), 'note' => 'Active catalog records'],
                ['label' => 'Support Scope', 'value' => $user->assignedAdminClient ? 'Assigned' : 'Unassigned', 'note' => $user->assignedAdminClient?->name ?? 'Developer can assign support later'],
            ],
            'rows' => collect([
                ['title' => 'Profile information', 'meta' => route('profile.edit'), 'note' => 'Name, email, password, and backup email.'],
                ['title' => 'Order history', 'meta' => route('orders.my.index'), 'note' => 'View active and completed print requests.'],
                ['title' => 'Service catalog', 'meta' => route('services.index'), 'note' => 'Browse available printing services.'],
            ]),
            'emptyMessage' => 'No settings records yet.',
        ]);
    }

    public function help(Request $request): View
    {
        $user = $request->user();

        return view('customer.sections.index', [
            'title' => 'Help Center',
            'kicker' => 'Customer Support',
            'description' => 'Support contacts and common service checkpoints for print requests.',
            'cards' => [
                ['label' => 'Assigned Admin Client', 'value' => $user->assignedAdminClient?->name ?? 'Pending', 'note' => $user->assignedAdminClient?->email ?? 'No assigned staff yet'],
                ['label' => 'Open Orders', 'value' => $user->orders()->whereIn('status', ['Pending', 'For Verification', 'Processing', 'Ready'])->count(), 'note' => 'Requests that may need follow-up'],
                ['label' => 'Services', 'value' => Service::where('is_active', true)->count(), 'note' => 'Available catalog entries'],
            ],
            'rows' => collect([
                ['title' => 'Pending', 'meta' => 'Order received', 'note' => 'Staff will review the request.'],
                ['title' => 'For Verification', 'meta' => 'Files or payment check', 'note' => 'Order may need validation before production.'],
                ['title' => 'Processing', 'meta' => 'Production stage', 'note' => 'Printing or preparation is underway.'],
                ['title' => 'Ready', 'meta' => 'Release stage', 'note' => 'Ready for pickup, delivery, or completion.'],
            ]),
            'emptyMessage' => 'No help records yet.',
        ]);
    }
}
