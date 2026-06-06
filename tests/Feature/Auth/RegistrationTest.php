<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'Password1!',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertAuthenticated();
        $this->assertSame('Test', $user->first_name);
        $this->assertSame('User', $user->last_name);
        $this->assertSame('Test User', $user->name);
        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->otp_code);
        Notification::assertSentTo($user, SendOTP::class);
        $response->assertRedirect(route('otp.verify', [
                'email' => 'test@example.com',
            ], false))
            ->assertSessionHas('otp_email', 'test@example.com')
            ->assertSessionHas('auth_type', 'account_verification')
            ->assertSessionMissing('password_reset_email')
            ->assertSessionMissing('password_reset_token')
            ->assertSessionMissing('is_forgot_password');
    }
}
