<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Pendaftaran\Models\Registration;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_registration_with_ajax(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->postJson('/pendaftarans', [
            'name' => 'Rani Putri',
            'email' => 'rani@example.test',
            'phone' => '08123456789',
            'address' => 'Bandung',
            'program' => 'Teknik Informatika',
            'status' => 'approved',
        ])->assertCreated()
            ->assertJsonPath('registration.status', 'pending')
            ->assertJsonPath('registration.program', 'Teknik Informatika');

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'email' => 'rani@example.test',
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_update_another_users_registration(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create(['role' => 'user']);
        $registration = Registration::factory()->for($owner)->create();

        $this->actingAs($otherUser)
            ->patchJson("/pendaftarans/{$registration->id}", ['name' => 'Tidak berhak'])
            ->assertNotFound();
    }

    public function test_user_only_sees_their_own_registrations(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create(['role' => 'user']);
        Registration::factory()->for($user)->create(['name' => 'Pendaftaran Saya']);
        Registration::factory()->for($otherUser)->create(['name' => 'Pendaftaran Rahasia']);

        $this->actingAs($user)
            ->get('/pendaftarans')
            ->assertOk()
            ->assertSee('Pendaftaran Saya')
            ->assertDontSee('Pendaftaran Rahasia');
    }

    public function test_user_can_update_and_delete_their_own_registration(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $registration = Registration::factory()->for($user)->create();

        $this->actingAs($user)
            ->patchJson("/pendaftarans/{$registration->id}", [
                'name' => 'Nama Diperbarui',
                'email' => $registration->email,
                'phone' => $registration->phone,
                'address' => $registration->address,
                'program' => $registration->program,
                'status' => 'approved',
            ])->assertOk()
            ->assertJsonPath('registration.name', 'Nama Diperbarui')
            ->assertJsonPath('registration.status', 'pending');

        $this->deleteJson("/pendaftarans/{$registration->id}")
            ->assertOk();

        $this->assertDatabaseMissing('registrations', ['id' => $registration->id]);
    }

    public function test_admin_can_change_registration_status(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $registration = Registration::factory()->for($owner)->create();

        $this->actingAs($admin)
            ->patchJson("/pendaftarans/{$registration->id}", [
                'name' => $registration->name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'address' => $registration->address,
                'program' => $registration->program,
                'status' => 'approved',
            ])->assertOk()
            ->assertJsonPath('registration.status', 'approved');
    }
}
