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
            'password_confirmation' => 'Password1!',
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

        $response = $this->post(route('admin.login.submit', absolute: false), [
            'email' => 'approved-client@example.com',
            'password' => 'Password1!',
        ]);

        $response
            ->assertRedirect(route('admin.otp.verify', absolute: false))
            ->assertSessionHas('needs_email_otp', true)
            ->assertSessionHas('admin_email', 'approved-client@example.com');

        $this->assertAuthenticatedAs($adminClient);
        $this->assertNotNull($adminClient->fresh()->otp_code);
        Mail::assertSent(OTPVerificationMail::class);
    }

    public function test_developer_login_bypasses_email_otp_and_opens_dashboard(): void
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

    public function test_admin_client_email_otp_grants_dashboard_access_without_authenticator_app(): void
    {
        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'approved-client@example.com',
            'password' => Hash::make('Password1!'),
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Approved Studio',
            'contact_person' => 'Approved Owner',
            'contact_number' => '09171111111',
            'business_address' => '456 Approved Street',
            'profile_completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($adminClient)
            ->withSession([
                'admin_auth_passed' => true,
                'admin_email' => $adminClient->email,
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

        $this->assertAuthenticatedAs($adminClient);
        $this->assertNull($adminClient->fresh()->otp_code);
    }

    public function test_admin_client_otp_requires_an_expiry_timestamp(): void
    {
        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'missing-expiry-client@example.com',
            'password' => Hash::make('Password1!'),
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
            'otp_code' => '123456',
            'otp_expires_at' => null,
            'email_verified_at' => null,
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Missing Expiry Studio',
            'contact_person' => 'Client Owner',
            'contact_number' => '09171111111',
            'business_address' => '123 Missing Expiry Street',
            'profile_completed_at' => now(),
        ]);

        $response = $this
            ->actingAs($adminClient)
            ->withSession([
                'admin_auth_passed' => true,
                'admin_email' => $adminClient->email,
                'needs_email_otp' => true,
            ])
            ->from(route('admin.otp.verify', absolute: false))
            ->post(route('admin.otp.submit', absolute: false), [
                'otp' => '123456',
            ]);

        $response
            ->assertRedirect(route('admin.otp.verify', absolute: false))
            ->assertSessionHasErrors('otp')
            ->assertSessionMissing('staff_otp_passed');

        $this->assertAuthenticatedAs($adminClient);
        $this->assertNull($adminClient->fresh()->email_verified_at);
    }

    public function test_admin_client_otp_uses_customer_three_attempt_lockout(): void
    {
        $developer = User::factory()->create(['role' => User::ROLE_DEVELOPER]);
        $adminClient = User::factory()->create([
            'role' => User::ROLE_ADMIN_CLIENT,
            'email' => 'otp-lockout-client@example.com',
            'password' => Hash::make('Password1!'),
            'approved_at' => now(),
            'approved_by' => $developer->id,
            'invitation_accepted_at' => now(),
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ]);

        $adminClient->adminClientProfile()->create([
            'business_name' => 'Lockout Studio',
            'contact_person' => 'Lockout Owner',
            'contact_number' => '09171111111',
            'business_address' => '789 Lockout Street',
            'profile_completed_at' => now(),
        ]);

        $session = [
            'admin_auth_passed' => true,
            'admin_email' => $adminClient->email,
            'needs_email_otp' => true,
        ];

        for ($attempt = 1; $attempt <= 2; $attempt++) {
            $this
                ->actingAs($adminClient)
                ->withSession($session)
                ->post(route('admin.otp.submit', absolute: false), [
                    'otp' => '000000',
                ])
                ->assertSessionHasErrors('otp');
        }

        $this
            ->actingAs($adminClient)
            ->withSession($session)
            ->post(route('admin.otp.submit', absolute: false), [
                'otp' => '000000',
            ])
            ->assertSessionHasErrors('otp');

        $throttleKey = 'staff-otp:' . Str::transliterate(Str::lower($adminClient->email . '|127.0.0.1'));

        $this->assertTrue(RateLimiter::tooManyAttempts($throttleKey, 3));
        $this->assertGreaterThan(0, RateLimiter::availableIn($throttleKey));
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
            ->assertRedirect(route('admin.login', absolute: false))
            ->assertSessionHasErrors('email')
            ->assertSessionMissing('admin_verified');

        $this->assertGuest();
    }
}
