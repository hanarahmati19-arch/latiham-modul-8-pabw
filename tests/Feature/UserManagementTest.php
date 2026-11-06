<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_users_cannot_access_user_management(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/usermanagements')
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/usermanagements')
            ->assertOk()
            ->assertSee('Manajemen pengguna');

        $this->post('/usermanagements', [
            'name' => 'Dina User',
            'email' => 'dina@example.test',
            'role' => 'user',
            'password' => 'long-password',
            'password_confirmation' => 'long-password',
        ])->assertRedirect(route('usermanagement.index'));

        $managedUser = User::query()->where('email', 'dina@example.test')->firstOrFail();

        $this->assertDatabaseHas('users', ['id' => $managedUser->id, 'role' => 'user']);

        $this->put(route('usermanagement.update', $managedUser), [
            'name' => 'Dina Admin',
            'email' => 'dina@example.test',
            'role' => 'admin',
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('usermanagement.index'));

        $this->assertDatabaseHas('users', ['id' => $managedUser->id, 'name' => 'Dina Admin', 'role' => 'admin']);

        $this->delete(route('usermanagement.destroy', $managedUser))
            ->assertRedirect(route('usermanagement.index'));

        $this->assertDatabaseMissing('users', ['id' => $managedUser->id]);
    }
}
