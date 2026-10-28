<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_visitor_can_create_individual_booking(): void
    {
        $visitor = User::create([
            'name' => 'Visitor Individu',
            'email' => 'visitor.ind@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Nasional Indonesia',
            'address' => 'Jl. Medan Merdeka Barat No. 12, Jakarta',
            'description' => 'Museum sejarah nasional Indonesia',
            'opening_time' => '08:00:00',
            'closing_time' => '17:00:00',
        ]);

        $ticket = Ticket::create([
            'museum_id' => $museum->id,
            'ticket_name' => 'Tiket Dewasa',
            'price' => 25000,
            'slot' => 100,
        ]);

        $response = $this->actingAs($visitor)->post(route('user.booking.store', $museum->id), [
            'visit_date' => now()->addDay()->toDateString(),
            'nama_penanggung_jawab' => 'Visitor Individu',
            'jumlah_anggota' => 2,
            'kota_asal' => 'Jakarta Pusat',
            'no_hp' => '081234567890',
            'ticket_'.$ticket->id => 2,
        ]);

        $booking = Booking::where('user_id', $visitor->id)->first();
        $this->assertNotNull($booking);
        $this->assertFalse((bool) $booking->is_rombongan);
        $this->assertEquals(50000, $booking->total_price);
        $response->assertRedirect(route('user.payment', $booking->id));
    }

    public function test_visitor_can_create_group_booking_with_manifest_minimum_6_pax(): void
    {
        $visitor = User::create([
            'name' => 'Ketua Rombongan',
            'email' => 'ketua.rombongan@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Fatahillah',
            'address' => 'Kota Tua Jakarta',
            'description' => 'Museum sejarah Jakarta',
            'opening_time' => '08:00:00',
            'closing_time' => '17:00:00',
        ]);

        $ticket = Ticket::create([
            'museum_id' => $museum->id,
            'ticket_name' => 'Tiket Rombongan Pelajar',
            'price' => 15000,
            'slot' => 200,
        ]);

        $manifest = [
            'Ahmad Fauzi',
            'Budi Santoso',
            'Citra Lestari',
            'Dewi Sartika',
            'Eko Prasetyo',
            'Fajar Ramadhan',
        ];

        $response = $this->actingAs($visitor)->post(route('user.booking.store', $museum->id), [
            'visit_date' => now()->addDay()->toDateString(),
            'nama_penanggung_jawab' => 'Ketua Rombongan',
            'jumlah_anggota' => 6,
            'kota_asal' => 'SMA 1 Jakarta',
            'no_hp' => '081234567890',
            'ticket_'.$ticket->id => 6,
            'manifest' => $manifest,
        ]);

        $booking = Booking::where('user_id', $visitor->id)->first();
        $this->assertNotNull($booking);
        $this->assertTrue((bool) $booking->is_rombongan);
        $this->assertEquals(90000, $booking->total_price);
        $this->assertEquals($manifest, $booking->manifest);
        $response->assertRedirect(route('user.payment', $booking->id));
    }

    public function test_group_booking_requires_manifest(): void
    {
        $visitor = User::create([
            'name' => 'Pemesan Rombongan Tanpa Manifes',
            'email' => 'grup.error@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Bahari',
            'address' => 'Jakarta Utara',
            'description' => 'Museum bahari',
            'opening_time' => '08:00:00',
            'closing_time' => '17:00:00',
        ]);

        $ticket = Ticket::create([
            'museum_id' => $museum->id,
            'ticket_name' => 'Tiket Umum',
            'price' => 10000,
            'slot' => 100,
        ]);

        $response = $this->actingAs($visitor)->post(route('user.booking.store', $museum->id), [
            'visit_date' => now()->addDay()->toDateString(),
            'nama_penanggung_jawab' => 'Ketua Rombongan',
            'jumlah_anggota' => 6,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'ticket_'.$ticket->id => 6,
            // manifest missing
        ]);

        $response->assertSessionHasErrors(['manifest']);
        $this->assertEquals(0, Booking::where('user_id', $visitor->id)->count());
    }

    public function test_visitor_can_simulate_midtrans_payment_confirmation(): void
    {
        $visitor = User::create([
            'name' => 'Pengunjung Bayar',
            'email' => 'visitor.pay@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Bank Indonesia',
            'address' => 'Jakarta Barat',
            'description' => 'Museum BI',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $booking = Booking::create([
            'user_id' => $visitor->id,
            'museum_id' => $museum->id,
            'nama_penanggung_jawab' => 'Pengunjung Bayar',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => now()->addDay()->toDateString(),
            'adult_qty' => 1,
            'student_qty' => 0,
            'child_qty' => 0,
            'total_price' => 10000,
            'status' => 'pending',
            'is_rombongan' => false,
        ]);

        // Process payment method to get transaction
        $processResponse = $this->actingAs($visitor)->post(route('user.payment.process', $booking->id), [
            'payment_method' => 'qris',
        ]);

        $transaction = Transaction::where('booking_id', $booking->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('pending', $transaction->payment_status);

        // View Midtrans show3 page
        $snapResponse = $this->actingAs($visitor)->get(route('user.payment.show3', $transaction->id));
        $snapResponse->assertStatus(200);

        // Confirm payment via Midtrans simulation
        $confirmResponse = $this->actingAs($visitor)->post(route('user.payment.confirm', $transaction->id));
        $confirmResponse->assertRedirect(route('user.transaction.show', $transaction->id));

        $transaction->refresh();
        $this->assertEquals('paid', $transaction->payment_status);
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

    public function test_admin_museum_cannot_access_super_admin_user_management(): void
    {
        $museum = Museum::create([
            'name' => 'Museum Pos Indonesia',
            'address' => 'Bandung',
            'description' => 'Museum pos',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $admin = User::create([
            'name' => 'Admin Biasa',
            'email' => 'admin.biasa@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'museum_id' => $museum->id,
        ]);

        $response = $this->actingAs($admin)->get(route('users.index'));
        $response->assertStatus(403);
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

    public function test_petugas_cannot_access_admin_dashboard(): void
    {
        $petugas = User::create([
            'name' => 'Petugas Loket',
            'email' => 'staff.loket@test.com',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        $response = $this->actingAs($petugas)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }
}
