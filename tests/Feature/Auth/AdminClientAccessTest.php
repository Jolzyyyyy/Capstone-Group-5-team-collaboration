<?php

namespace Tests\Feature\Auth;

use App\Mail\OTPVerificationMail;
use App\Models\User;
use App\Notifications\AdminClientInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminClientAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_preregister_admin_client_with_hashed_invite_token(): void
    {
        Notification::fake();

        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->post(route('developer.admin-clients.store', absolute: false), [
                'name' => 'Client Manager',
                'email' => 'client-manager@example.com',
            ]);

        $adminClient = User::where('email', 'client-manager@example.com')->firstOrFail();

        $response
            ->assertRedirect(route('developer.admin-clients.index', absolute: false))
            ->assertSessionHas('invite_url');

        $this->assertSame(User::ROLE_ADMIN_CLIENT, $adminClient->role);
        $this->assertSame($developer->id, $adminClient->preregistered_by);
        $this->assertNull($adminClient->approved_at);
        $this->assertNotNull($adminClient->invite_token);
        $this->assertNotSame(session('invite_url'), $adminClient->invite_token);

        Notification::assertSentTo($adminClient, AdminClientInvitation::class);
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'admin_client_preregistered',
            'actor_id' => $developer->id,
            'target_user_id' => $adminClient->id,
        ]);
    }

    public function test_invite_acceptance_keeps_admin_client_email_unverified_until_portal_otp(): void
    {
        $token = 'plain-admin-client-token';
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'accepted-client@example.com',
            'password' => Hash::make('temporary-password'),
            'email_verified_at' => now(),
            'invite_token' => hash('sha256', $token),
            'invite_expires_at' => now()->addDay(),
            'approved_at' => null,
        ]);

        $response = $this->post(route('admin-client-invitations.store', [
            'token' => $token,
        ], false), [
            'password' => 'Password1!',
            'business_name' => 'Client Studio',
            'contact_person' => 'Client Owner',
            'contact_number' => '09170000000',
            'business_address' => '123 Client Street',
            'reference_notes' => 'Primary branch',
        ]);

        $adminClient->refresh();

        $response->assertRedirect(route('admin.login', absolute: false));
        $this->assertTrue(Hash::check('Password1!', $adminClient->password));
        $this->assertNull($adminClient->email_verified_at);
        $this->assertNull($adminClient->invite_token);
        $this->assertNull($adminClient->invite_expires_at);
        $this->assertNotNull($adminClient->invitation_accepted_at);
        $this->assertDatabaseHas('admin_client_profiles', [
            'user_id' => $adminClient->id,
            'business_name' => 'Client Studio',
            'contact_person' => 'Client Owner',
        ]);
    }

    public function test_approved_admin_client_login_requires_email_otp_before_dashboard(): void
    {
        Mail::fake();

        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'approved-client@example.com',
            'password' => Hash::make('Password1!'),
            'email_verified_at' => null,
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Approved Studio',
            'contact_person' => 'Approved Owner',
            'contact_number' => '09171111111',
            'business_address' => '456 Approved Street',
            'profile_completed_at' => now(),
        ]);

        $response = $this
            ->withSession(['staff_otp_passed' => true])
            ->post(route('admin.login.submit', absolute: false), [
                'email' => 'approved-client@example.com',
                'password' => 'Password1!',
            ]);

        $response
            ->assertRedirect(route('admin.otp.verify', absolute: false))
            ->assertSessionHas('needs_email_otp', true)
            ->assertSessionHas('admin_email', 'approved-client@example.com')
            ->assertSessionMissing('staff_otp_passed');

        $this->assertAuthenticatedAs($adminClient);
        $this->assertNotNull($adminClient->fresh()->otp_code);
        Mail::assertSent(OTPVerificationMail::class);
    }

    public function test_staff_email_otp_locks_after_three_failed_attempts(): void
    {
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'locked-client@example.com',
            'password' => Hash::make('Password1!'),
            'email_verified_at' => null,
            'approved_at' => now(),
            'invitation_accepted_at' => now(),
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        $session = [
            'admin_auth_passed' => true,
            'admin_email' => $adminClient->email,
            'needs_email_otp' => true,
        ];

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this
                ->actingAs($adminClient)
                ->withSession($session)
                ->post(route('admin.otp.submit', absolute: false), [
                    'otp' => '000000',
                ])
                ->assertSessionHasErrors('otp');
        }

        $this->assertTrue(RateLimiter::tooManyAttempts(
            'staff-otp:' . Str::transliterate(Str::lower($adminClient->email) . '|127.0.0.1'),
            3
        ));
        $this->assertFalse(session('staff_otp_passed', false));
    }

    public function test_staff_registration_form_uses_single_password_field(): void
    {
        $response = $this->get(route('admin.register', absolute: false));

        $response
            ->assertOk()
            ->assertSee('name="password"', false)
            ->assertDontSee('name="password_confirmation"', false);
    }

    public function test_developer_registration_bypasses_email_otp_verification(): void
    {
        Mail::fake();

        $response = $this->post(route('admin.register.submit', absolute: false), [
            'name' => 'Portal Developer',
            'email' => 'portal-developer@example.com',
            'role' => User::ROLE_DEVELOPER,
            'password' => 'Password1!',
        ]);

        $user = User::where('email', 'portal-developer@example.com')->firstOrFail();

        $response
            ->assertRedirect(route('admin.dashboard', absolute: false))
            ->assertSessionHas('staff_otp_passed', true)
            ->assertSessionMissing('admin_auth_passed')
            ->assertSessionMissing('needs_email_otp');

        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::ROLE_DEVELOPER, $user->role);
        $this->assertNull($user->fresh()->otp_code);
        Mail::assertNothingSent();
    }

    public function test_developer_approval_keeps_invited_account_as_admin_client(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $pendingAdmin = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'pending-admin@example.com',
            'password' => Hash::make('Password1!'),
            'email_verified_at' => null,
            'approved_at' => null,
            'approved_by' => null,
            'invitation_accepted_at' => now(),
        ]);

        $pendingAdmin->adminClientProfile()->create([
            'business_name' => 'Admin Studio',
            'contact_person' => 'Pending Admin',
            'contact_number' => '09173333333',
            'business_address' => 'Admin Street',
            'profile_completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->patch(route('developer.admin-clients.approve', $pendingAdmin, false));

        $pendingAdmin->refresh();

        $response->assertRedirect(route('developer.admin-clients.index', absolute: false));
        $this->assertSame(User::ROLE_ADMIN_CLIENT, $pendingAdmin->role);
        $this->assertNotNull($pendingAdmin->approved_at);
        $this->assertSame($developer->id, $pendingAdmin->approved_by);
    }

    public function test_verified_developer_login_bypasses_email_otp_each_session(): void
    {
        Mail::fake();

        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email' => 'developer@example.com',
            'password' => Hash::make('Password1!'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('admin.login.submit', absolute: false), [
            'email' => 'developer@example.com',
            'password' => 'Password1!',
        ]);

        $response
            ->assertRedirect(route('admin.dashboard', absolute: false))
            ->assertSessionHas('staff_otp_passed', true)
            ->assertSessionMissing('needs_email_otp');

        $this->assertAuthenticatedAs($developer);
        $this->assertNull($developer->fresh()->otp_code);
        Mail::assertNothingSent();
    }

    public function test_staff_email_otp_grants_dashboard_access_without_authenticator_app(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email' => 'admin@example.com',
            'password' => Hash::make('Password1!'),
            'email_verified_at' => now(),
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        $response = $this
            ->actingAs($admin)
            ->withSession([
                'admin_auth_passed' => true,
                'admin_email' => $admin->email,
                'needs_email_otp' => true,
            ])
            ->post(route('admin.otp.submit', absolute: false), [
                'otp' => '123456',
            ]);

        $response
            ->assertRedirect(route('admin.dashboard', absolute: false))
            ->assertSessionHas('staff_otp_passed', true)
            ->assertSessionMissing('admin_verified')
            ->assertSessionMissing('2fa_passed');

        $this->assertAuthenticatedAs($admin);
        $this->assertNull($admin->fresh()->otp_code);
    }

    public function test_suspending_admin_client_revokes_email_verification_and_2fa(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email_verified_at' => now(),
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'google2fa_enabled' => true,
            'google2fa_secret' => 'JBSWY3DPEHPK3PXP',
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->patch(route('developer.admin-clients.suspend', $adminClient, false));

        $adminClient->refresh();

        $response->assertRedirect(route('developer.admin-clients.index', absolute: false));
        $this->assertNull($adminClient->approved_at);
        $this->assertNull($adminClient->approved_by);
        $this->assertNull($adminClient->email_verified_at);
        $this->assertFalse($adminClient->google2fa_enabled);
        $this->assertNull($adminClient->google2fa_secret);
    }

    public function test_legacy_authenticator_route_redirects_after_email_otp(): void
    {
        $developer = User::factory()->create([
            'role' => User::ROLE_DEVELOPER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($developer)
            ->withSession(['staff_otp_passed' => true])
            ->get(route('admin.security.2fa', absolute: false));

        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_customer_credentials_are_rejected_by_staff_developer_portal(): void
    {
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email' => 'customer@example.com',
            'password' => Hash::make('Password1!'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post(route('admin.login.submit', absolute: false), [
            'email' => $customer->email,
            'password' => 'Password1!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_customer_cannot_access_staff_developer_dashboard(): void
    {
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($customer)
            ->withSession(['customer_otp_passed' => true])
            ->get(route('admin.dashboard', absolute: false));

        $response->assertForbidden();
    }

    public function test_customer_cannot_submit_staff_portal_otp(): void
    {
        $customer = User::factory()->create([
            'role' => User::ROLE_CUSTOMER,
            'email_verified_at' => null,
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        $response = $this
            ->actingAs($customer)
            ->post(route('admin.otp.submit', absolute: false), [
                'otp' => '123456',
            ]);

        $response
            ->assertForbidden()
            ->assertSessionMissing('admin_verified');

        $this->assertAuthenticatedAs($customer);
    }
}
