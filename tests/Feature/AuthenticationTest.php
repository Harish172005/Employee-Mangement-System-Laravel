<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_and_is_redirected_to_the_dashboard(): void
    {
        $user = User::factory()->admin()->create();

        $this->post(route('login.attempt'), [
            'username' => $user->username,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('success', 'Logged in successfully.');

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_returns_a_validation_error(): void
    {
        $this->from(route('login'))
            ->post(route('login.attempt'), [
                'username' => 'missing-user',
                'password' => 'incorrect',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_inactive_account_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create();

        $this->from(route('login'))
            ->post(route('login.attempt'), [
                'username' => $user->username,
                'password' => 'password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', 'Logged out successfully.');

        $this->assertGuest();
    }
}
