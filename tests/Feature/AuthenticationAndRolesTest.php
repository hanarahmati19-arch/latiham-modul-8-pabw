<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAndRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_regular_user_and_logs_them_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ayu Pratama',
            'email' => 'ayu@example.test',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'ayu@example.test',
            'role' => 'user',
        ]);
    }

    public function test_user_can_sign_in_and_sign_out(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
