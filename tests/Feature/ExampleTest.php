<?php

namespace Tests\Feature;

use App\Models\Museum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::create([
            'name' => 'Pengunjung Test',
            'email' => 'visitor@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_petugas_can_view_qrcodes_page(): void
    {
        $petugas = User::create([
            'name' => 'Petugas Test',
            'email' => 'petugas@test.com',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        $response = $this->actingAs($petugas)->get(route('petugas.qrcodes.index'));

        $response->assertStatus(200);
    }

    public function test_super_admin_can_create_admin_museum_with_museum_assignment(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Geologi Bandung',
            'address' => 'Jl. Diponegoro No. 57, Bandung',
            'description' => 'Museum geologi bersejarah',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $response = $this->actingAs($superAdmin)->post(route('users.store'), [
            'name' => 'Admin Geologi',
            'email' => 'admin.geologi@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
            'museum_id' => $museum->id,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'admin.geologi@test.com',
            'role' => 'admin',
            'museum_id' => $museum->id,
        ]);
    }

    public function test_admin_museum_can_manage_petugas_for_own_museum(): void
    {
        $museum = Museum::create([
            'name' => 'Museum Barli',
            'address' => 'Bandung',
            'description' => 'Museum seni',
            'opening_time' => '08:00:00',
            'closing_time' => '17:00:00',
        ]);

        $admin = User::create([
            'name' => 'Admin Barli',
            'email' => 'admin.barli@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'museum_id' => $museum->id,
        ]);

        $indexResponse = $this->actingAs($admin)->get(route('admin.petugas.index'));
        $indexResponse->assertStatus(200);

        $createResponse = $this->actingAs($admin)->post(route('admin.petugas.store'), [
            'name' => 'Petugas Barli 1',
            'email' => 'petugas.barli@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $createResponse->assertRedirect(route('admin.petugas.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'petugas.barli@test.com',
            'role' => 'staff',
            'museum_id' => $museum->id,
        ]);
    }
}
