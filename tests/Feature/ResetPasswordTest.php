<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_reset_password_page()
    {
        $token = 'sample-token';
        $email = 'test@example.com';

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => $email]));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.reset');
        $response->assertSee('Reset Password');
    }

    /** @test */
    public function user_can_reset_password_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /** @test */
    public function password_reset_fails_for_invalid_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function password_reset_fails_for_invalid_email()
    {
        $response = $this->post(route('password.update'), [
            'token' => 'valid-token',
            'email' => 'invalid@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function password_reset_fails_if_passwords_do_not_match()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
