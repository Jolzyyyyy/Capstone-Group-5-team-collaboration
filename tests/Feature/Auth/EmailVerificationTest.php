<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $user->forceFill([
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        $response = $this->actingAs($user)->post(route('verification.verify'), [
            'otp' => '123456',
        ]);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_email_is_not_verified_with_invalid_otp(): void
    {
        $user = User::factory()->unverified()->create();

        $user->forceFill([
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(User::EMAIL_OTP_TTL_MINUTES),
        ])->save();

        $response = $this->actingAs($user)->from('/verify-email')->post(route('verification.verify'), [
            'otp' => '654321',
        ]);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $response
            ->assertSessionHasErrors('otp')
            ->assertRedirect('/verify-email');
    }
}
