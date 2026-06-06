<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, SendOTP::class);
        $this->assertNotNull($user->fresh()->otp_code);
        $response
            ->assertSessionHas('password_reset_token')
            ->assertRedirect(route('otp.verify', [
                'email' => $user->email,
                'flow' => 'forgot_password',
            ], false));
    }

    public function test_password_reset_request_does_not_reveal_unknown_email(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'missing@example.com',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'If that email belongs to an account, a verification code has been sent.');

        Notification::assertNothingSent();
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $token = 'test-reset-token';

        $response = $this
            ->withSession([
                'password_reset_token' => $token,
                'password_reset_email' => $user->email,
            ])
            ->get(route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ], false));

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_after_otp_context_is_verified(): void
    {
        $user = User::factory()->create();
        $token = 'test-reset-token';

        $response = $this
            ->withSession([
                'password_reset_token' => $token,
                'password_reset_email' => $user->email,
            ])
            ->post(route('password.store', absolute: false), [
                'token' => $token,
                'email' => $user->email,
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
                'action_type' => 'manual_login',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login', absolute: false));

        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }

    public function test_forgot_password_otp_reset_flow_can_auto_login_user(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'reset-flow@example.com',
            'password' => Hash::make('old-password'),
        ]);

        $this->post('/forgot-password', [
            'email' => $user->email,
        ])->assertRedirect(route('otp.verify', [
            'email' => $user->email,
            'flow' => 'forgot_password',
        ], false));

        $otp = $user->fresh()->otp_code;

        $this->post(route('customer.otp.submit', absolute: false), [
            'email' => $user->email,
            'otp' => $otp,
        ])->assertRedirect(route('password.reset', [
            'token' => session('password_reset_token'),
            'email' => $user->email,
        ], false));

        $this->post(route('password.store', absolute: false), [
            'token' => session('password_reset_token'),
            'email' => $user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
            'action_type' => 'auto_login',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }
}
