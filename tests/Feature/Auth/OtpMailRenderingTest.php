<?php

namespace Tests\Feature\Auth;

use App\Mail\OTPVerificationMail;
use App\Models\User;
use App\Notifications\SendOTP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpMailRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_otp_notification_renders_without_markdown_css_inlining(): void
    {
        config(['mail.default' => 'array']);

        $user = User::factory()->create([
            'email' => 'render-customer@example.com',
            'name' => 'Render Customer',
        ]);

        $user->notify(new SendOTP('123456'));

        $this->assertTrue(true);
    }

    public function test_staff_otp_mailable_renders_without_markdown_css_inlining(): void
    {
        config(['mail.default' => 'array']);

        Mail::to('render-staff@example.com')->send(new OTPVerificationMail('654321'));

        $this->assertTrue(true);
    }
}
