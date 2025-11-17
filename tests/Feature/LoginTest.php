<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);
    }

    /** @test */
    public function user_can_login_successfully()
    {
        DB::table('settings')->updateOrInsert(
            ['key' => 'email_2fa_status'],
            ['value' => 'off']
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'sms_2fa_status'],
            ['value' => 'off']
        );

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function login_fails_with_wrong_password()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function inactive_users_cannot_login()
    {
        $inactiveUser = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password123'),
            'status' => 0,
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHas('error', 'Your account is inactive. Please contact the administrator.');
        $this->assertGuest();
    }

    /** @test */
    public function otp_is_sent_when_2fa_is_enabled()
    {
        Mail::fake();
        Cache::shouldReceive('put')->once();

        // Enable 2FA in the database settings
        DB::table('settings')->updateOrInsert(
            ['key' => 'email_2fa_status'],
            ['value' => 'on']
        );

        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('otp.verify'));
        Mail::assertSent(\App\Mail\OtpMail::class);
    }
}
