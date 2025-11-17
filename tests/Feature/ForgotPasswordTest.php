<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function user_can_view_reset_password_page()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Reset Password');
    }

    /** @test */
    public function user_receives_password_reset_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status', trans(Password::RESET_LINK_SENT));
    }

    /** @test */
    public function password_reset_fails_for_non_existing_email()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'nonexisting@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function password_reset_fails_for_invalid_email_format()
    {
        $response = $this->post(route('password.email'), [
            'email' => 'invalid-email-format',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }
}
