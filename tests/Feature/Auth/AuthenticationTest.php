<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        Notification::assertSentTo($user, SendOTP::class);
        $this->assertNotNull($user->fresh()->otp_code);

        $response
            ->assertRedirect(route('otp.verify', [
                'email' => $user->email,
            ], false))
            ->assertSessionHas('otp_email', $user->email)
            ->assertSessionHas('auth_type', 'account_verification')
            ->assertSessionMissing('customer_otp_passed');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_normal_login_clears_stale_password_reset_session(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this
            ->withSession([
                'password_reset_email' => $user->email,
                'password_reset_token' => 'stale-token',
                'is_forgot_password' => true,
                'auth_type' => 'forgot_password',
            ])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, SendOTP::class);

        $response
            ->assertRedirect(route('otp.verify', [
                'email' => $user->email,
            ], false))
            ->assertSessionHas('otp_email', $user->email)
            ->assertSessionHas('auth_type', 'account_verification')
            ->assertSessionMissing('password_reset_email')
            ->assertSessionMissing('password_reset_token')
            ->assertSessionMissing('is_forgot_password');
    }

    public function test_fresh_customer_login_clears_stale_otp_passed_session(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this
            ->withSession(['customer_otp_passed' => true])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, SendOTP::class);

        $response
            ->assertRedirect(route('otp.verify', [
                'email' => $user->email,
            ], false))
            ->assertSessionMissing('customer_otp_passed');
    }

    public function test_customer_otp_requires_an_expiry_timestamp(): void
    {
        $user = User::factory()->create([
            'email' => 'missing-expiry@example.com',
            'otp_code' => '123456',
            'otp_expires_at' => null,
            'email_verified_at' => null,
        ]);

        $response = $this
            ->withSession([
                'otp_email' => $user->email,
                'auth_type' => 'account_verification',
            ])
            ->from(route('otp.verify', absolute: false))
            ->post(route('otp.submit', absolute: false), [
                'email' => $user->email,
                'otp' => '123456',
            ]);

        $response
            ->assertRedirect(route('otp.verify', absolute: false))
            ->assertSessionHasErrors('otp')
            ->assertSessionMissing('customer_otp_passed');

        $this->assertGuest();
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_customer_otp_verification_rejects_mismatched_authenticated_email(): void
    {
        $signedInUser = User::factory()->create([
            'email' => 'signed-in@example.com',
            'otp_code' => '111111',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
            'email_verified_at' => null,
        ]);

        $otherUser = User::factory()->create([
            'email' => 'other-user@example.com',
            'otp_code' => '222222',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
            'email_verified_at' => null,
        ]);

        $response = $this
            ->actingAs($signedInUser)
            ->withSession([
                'otp_email' => $signedInUser->email,
                'auth_type' => 'account_verification',
            ])
            ->from(route('otp.verify', absolute: false))
            ->post(route('otp.submit', absolute: false), [
                'email' => $otherUser->email,
                'otp' => '222222',
            ]);

        $response
            ->assertRedirect(route('otp.verify', absolute: false))
            ->assertSessionHasErrors('email')
            ->assertSessionMissing('customer_otp_passed');

        $this->assertNull($signedInUser->fresh()->email_verified_at);
        $this->assertNull($otherUser->fresh()->email_verified_at);
    }

    public function test_customer_otp_uses_three_attempt_lockout(): void
    {
        $user = User::factory()->create([
            'email' => 'otp-lockout-customer@example.com',
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
            'email_verified_at' => null,
        ]);

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $this
                ->actingAs($user)
                ->withSession([
                    'otp_email' => $user->email,
                    'auth_type' => 'account_verification',
                ])
                ->post(route('otp.submit', absolute: false), [
                    'email' => $user->email,
                    'otp' => '000000',
                ])
                ->assertSessionHasErrors('otp');
        }

        $throttleKey = 'customer-otp:' . Str::transliterate(Str::lower($user->email . '|127.0.0.1'));

        $this->assertTrue(RateLimiter::tooManyAttempts($throttleKey, 3));
        $this->assertGreaterThan(0, RateLimiter::availableIn($throttleKey));
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
