<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
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

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
