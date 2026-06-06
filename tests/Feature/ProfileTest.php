<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_customer_profile_details_are_saved_to_the_account_record(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->patchJson('/profile', [
                'name' => 'Maria Santos',
                'email' => 'customer@example.com',
                'phone' => '+639171112222',
                'birthdate' => '1998-04-12',
                'gender' => 'Female',
                'street' => 'Blk 6 Lot 8',
                'barangay' => 'Ninada',
                'region' => 'Metro Manila',
                'city' => 'Quezon City',
                'postal_code' => '1121',
                'company' => 'Printify Co.',
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('ok', true);

        $user->refresh();

        $this->assertSame('Maria Santos', $user->name);
        $this->assertSame('+639171112222', $user->phone);
        $this->assertSame('1998-04-12', $user->birthdate);
        $this->assertSame('Female', $user->gender);
        $this->assertSame('Blk 6 Lot 8', $user->street);
        $this->assertSame('Ninada', $user->barangay);
        $this->assertSame('Metro Manila', $user->region);
        $this->assertSame('Quezon City', $user->city);
        $this->assertSame('1121', $user->postal_code);
        $this->assertSame('Printify Co.', $user->company);
    }

    public function test_backup_email_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile/backup-email', [
                'backup_email' => 'backup@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertSame('backup@example.com', $user->refresh()->backup_email);
    }

    public function test_backup_email_cannot_match_primary_email(): void
    {
        $user = User::factory()->create([
            'email' => 'primary@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->patch('/profile/backup-email', [
                'backup_email' => 'primary@example.com',
            ]);

        $response
            ->assertSessionHasErrorsIn('backupEmail', 'backup_email')
            ->assertRedirect('/profile');
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
