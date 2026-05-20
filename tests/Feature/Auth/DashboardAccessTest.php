<?php

namespace Tests\Feature\Auth;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_dashboard_renders_for_verified_customer(): void
    {
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($customer)
            ->withSession(['customer_otp_passed' => true])
            ->get(route('dashboard', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Client Portal')
            ->assertSee('Dashboard Overview')
            ->assertSee('Customer');
    }

    public function test_developer_dashboard_renders_developer_controls(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
            'google2fa_enabled' => true,
            'google2fa_secret' => 'JBSWY3DPEHPK3PXP',
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.dashboard', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Developer Dashboard')
            ->assertSee('Manage Admin Clients')
            ->assertSee('Recent Audit Activity');
    }

    public function test_admin_dashboard_does_not_render_developer_controls(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.dashboard', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Dashboard Overview')
            ->assertSee('Admin Portal')
            ->assertDontSee('Developer Dashboard')
            ->assertDontSee('Manage Admin Clients');
    }

    public function test_admin_cannot_access_developer_admin_client_management(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('developer.admin-clients.index', absolute: false));

        $response->assertForbidden();
    }

    public function test_developer_admin_client_management_uses_developer_portal_shell(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('developer.admin-clients.index', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Developer Portal')
            ->assertSee('Developer')
            ->assertSee('Manage Admin Clients')
            ->assertDontSee('Client Portal')
            ->assertDontSee('My Orders');
    }

    public function test_developer_dashboard_sidebar_uses_required_sections_only(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.dashboard', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Go to Home')
            ->assertSee('Dashboard')
            ->assertSee('Manage Admin Clients')
            ->assertSee('Orders')
            ->assertSee('Services')
            ->assertSee('Customers')
            ->assertSee('Reports')
            ->assertSee('Analytics')
            ->assertSee('Settings')
            ->assertDontSee('Products')
            ->assertDontSee('Rates')
            ->assertDontSee('Help Center')
            ->assertDontSee('Customer/User');
    }

    public function test_admin_client_dashboard_renders_after_approval_and_profile_completion(): void
    {
        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email_verified_at' => now(),
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
            'google2fa_enabled' => true,
            'google2fa_secret' => 'JBSWY3DPEHPK3PXP',
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Dashboard Studio',
            'contact_person' => 'Dashboard Owner',
            'contact_number' => '09172222222',
            'business_address' => '789 Dashboard Street',
            'profile_completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($adminClient)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.dashboard', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Admin Client Dashboard')
            ->assertSee('Access Checklist')
            ->assertSee('Reference Profile');
    }

    public function test_developer_can_assign_customer_to_admin_client_and_move_existing_orders(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'approved_at' => now(),
            'approved_by' => $developer->id,
        ]);

        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email' => 'customer-scope@example.com',
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'status' => 'Pending',
            'total_price' => 150,
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->patch(route('developer.admin-clients.assign-customer', $adminClient, false), [
                'customer_email' => 'customer-scope@example.com',
            ]);

        $response->assertRedirect(route('developer.admin-clients.index', absolute: false));
        $this->assertSame($adminClient->id, $customer->fresh()->admin_client_id);
        $this->assertSame($adminClient->id, $order->fresh()->admin_client_id);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'customer_assigned_to_admin_client',
            'actor_id' => $developer->id,
            'target_user_id' => $customer->id,
        ]);
    }

    public function test_admin_client_order_database_is_limited_to_assigned_records(): void
    {
        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
        ]);
        $otherAdminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Scope Studio',
            'contact_person' => 'Scope Owner',
            'contact_number' => '09173333333',
            'business_address' => '100 Scope Street',
            'profile_completed_at' => now(),
        ]);

        $assignedCustomer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'name' => 'Assigned Customer',
            'admin_client_id' => $adminClient->id,
        ]);
        $otherCustomer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'name' => 'Other Customer',
            'admin_client_id' => $otherAdminClient->id,
        ]);

        $assignedOrder = Order::create([
            'user_id' => $assignedCustomer->id,
            'admin_client_id' => $adminClient->id,
            'customer_name' => 'Assigned Customer',
            'customer_email' => $assignedCustomer->email,
            'status' => 'Processing',
            'total_price' => 250,
        ]);
        $otherOrder = Order::create([
            'user_id' => $otherCustomer->id,
            'admin_client_id' => $otherAdminClient->id,
            'customer_name' => 'Other Customer',
            'customer_email' => $otherCustomer->email,
            'status' => 'Pending',
            'total_price' => 300,
        ]);

        $response = $this
            ->actingAs($adminClient)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.orders.index', absolute: false));

        $response
            ->assertOk()
            ->assertSee('Assigned Customer')
            ->assertDontSee('Other Customer');

        $this
            ->actingAs($adminClient)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.customers.index', absolute: false))
            ->assertOk()
            ->assertSee('Assigned Customer')
            ->assertDontSee('Other Customer');

        $this
            ->actingAs($adminClient)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.orders.show', $otherOrder, false))
            ->assertForbidden();

        $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.orders.show', $assignedOrder, false))
            ->assertOk();
    }

    public function test_admin_client_can_view_but_not_manage_service_catalog(): void
    {
        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Service Scope Studio',
            'contact_person' => 'Service Owner',
            'contact_number' => '09174444444',
            'business_address' => '200 Service Street',
            'profile_completed_at' => now(),
        ]);

        $service = Service::create([
            'name' => 'Restricted Service',
            'category' => 'Printing',
            'retail_price' => 10,
            'bulk_price' => 8,
            'unit' => 'page',
            'is_active' => true,
        ]);

        $this
            ->actingAs($adminClient)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.services.index', absolute: false))
            ->assertOk()
            ->assertSee('Restricted Service')
            ->assertSee('View only');

        $this
            ->actingAs($adminClient)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.services.edit', $service, false))
            ->assertForbidden();
    }
}
