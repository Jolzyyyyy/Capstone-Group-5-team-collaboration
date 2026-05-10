<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_login_creates_customer_and_requires_email_otp(): void
    {
        Notification::fake();
        $this->configureGoogle();
        $this->fakeGoogleUser('google-123', 'google.customer@example.com', 'Google Customer');

        $response = $this->get(route('google.callback', absolute: false));

        $user = User::where('email', 'google.customer@example.com')->firstOrFail();

        $response->assertRedirect(route('otp.verify', [
            'email' => 'google.customer@example.com',
        ], false));

        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertSame('google-123', $user->google_id);
        $this->assertFalse($user->has_set_password);
        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->otp_code);

        Notification::assertSentTo($user, SendOTP::class);
    }

    public function test_google_login_rejects_staff_and_developer_accounts(): void
    {
        $this->configureGoogle();
        $this->fakeGoogleUser('google-staff', 'developer@example.com', 'Developer User');

        $developer = User::factory()->create([
            'email' => 'developer@example.com',
            'role' => User::ROLE_DEVELOPER,
        ]);

        $response = $this->get(route('google.callback', absolute: false));

        $response->assertRedirect(route('admin.login', absolute: false));
        $this->assertGuest();
        $this->assertNull($developer->fresh()->google_id);
    }

    private function configureGoogle(): void
    {
        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    private function fakeGoogleUser(string $id, string $email, string $name): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($id);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getName')->andReturn($name);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);
    }
}
