<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasFeaturesACDETest extends TestCase
{
    use RefreshDatabase;

    private User $petugasUser;

    private User $visitorUser;

    private Museum $museum;

    private Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-08-16 10:00:00');

        $this->museum = Museum::create([
            'name' => 'Museum Bahari Jakarta',
            'category' => 'museum',
            'address' => 'Jl. Pasar Ikan No. 1, Jakarta Utara',
            'description' => 'Museum sejarah kelautan nusantara.',
            'opening_time' => '00:00',
            'closing_time' => '23:59',
            'operational_hours' => [
                'senin' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
                'selasa' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
                'rabu' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
                'kamis' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
                'jumat' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
                'sabtu' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
                'minggu' => ['closed' => false, 'sessions' => [['open' => '00:00', 'close' => '23:59']]],
            ],
            'facilities' => ['Mushola', 'Area Parkir', 'Toilet Bersih'],
        ]);

        $this->ticket = Ticket::create([
            'museum_id' => $this->museum->id,
            'ticket_name' => 'Tiket Rombongan',
            'price' => 20000,
            'slot' => 200,
        ]);

        $this->petugasUser = User::create([
            'name' => 'Petugas Gate 1',
            'email' => 'petugas1@test.com',
            'password' => bcrypt('password123'),
            'role' => 'staff',
            'museum_id' => $this->museum->id,
        ]);

        $this->visitorUser = User::create([
            'name' => 'Ketua Rombongan SMP',
            'email' => 'rombongan@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * Fitur A Petugas: Validasi rombongan bertahap mengupdate jumlah pax dan menjaga tiket tetap aktif untuk sisa anggota.
     */
    public function test_partial_group_manifest_checkin_updates_pax_and_keeps_ticket_active(): void
    {
        $manifest = ['Budi', 'Siti', 'Agus', 'Dewi', 'Rian', 'Eka'];

        $booking = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museum->id,
            'nama_penanggung_jawab' => 'Ketua Rombongan SMP',
            'jumlah_anggota' => 6,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => now()->toDateString(),
            'total_price' => 120000,
            'status' => 'paid',
            'is_rombongan' => true,
            'manifest' => $manifest,
        ]);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'ITH-ROMBONGAN01',
            'payment_method' => 'qris',
            'subtotal' => 120000,
            'total_amount' => 120000,
            'payment_status' => 'paid',
        ]);

        // Gelombang 1: Hanya 3 orang pertama yang datang ('Budi', 'Siti', 'Agus')
        $response = $this->actingAs($this->petugasUser)->post(route('petugas.qrcodes.validate'), [
            'invoice_code' => 'ITH-ROMBONGAN01',
            'manifest_checked' => ['Budi', 'Siti', 'Agus'],
        ]);

        $response->assertSessionHas('success');
        $transaction->refresh();

        $this->assertEquals(3, $transaction->scanned_pax);
        $this->assertEquals(3, $transaction->remainingPax());
        $this->assertNull($transaction->used_at); // Tiket belum habis karena masih ada 3 orang lagi
        $this->assertFalse($transaction->isFullyUsed());
        $this->assertContains('Budi', $transaction->scanned_manifest);
        $this->assertContains('Siti', $transaction->scanned_manifest);

        // Gelombang 2: Sisa 3 orang lagi datang ('Dewi', 'Rian', 'Eka')
        $response2 = $this->actingAs($this->petugasUser)->post(route('petugas.qrcodes.validate'), [
            'invoice_code' => 'ITH-ROMBONGAN01',
            'manifest_checked' => ['Dewi', 'Rian', 'Eka'],
        ]);

        $response2->assertSessionHas('success');
        $transaction->refresh();

        $this->assertEquals(6, $transaction->scanned_pax);
        $this->assertEquals(0, $transaction->remainingPax());
        $this->assertNotNull($transaction->used_at); // Sekarang sudah komplit dan ditandai used
        $this->assertTrue($transaction->isFullyUsed());
    }

    /**
     * Fitur C Petugas: Endpoint offline cache mengembalikan daftar tiket valid hari ini.
     */
    public function test_offline_cache_endpoint_returns_valid_today_tickets(): void
    {
        $today = now()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museum->id,
            'nama_penanggung_jawab' => 'Tamu Offline',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => $today,
            'total_price' => 20000,
            'status' => 'paid',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'ITH-OFFLINECACHE01',
            'payment_method' => 'qris',
            'subtotal' => 20000,
            'total_amount' => 20000,
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->petugasUser)->getJson(route('petugas.scan.offline-cache'));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'invoice_code' => 'ITH-OFFLINECACHE01',
            'customer_name' => 'Tamu Offline',
            'is_fully_used' => false,
        ]);
    }

    /**
     * Fitur C Petugas: Sinkronisasi scan offline memperbarui status transaksi dan mencatat log.
     */
    public function test_sync_offline_scans_updates_transactions_and_creates_logs(): void
    {
        $today = now()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museum->id,
            'nama_penanggung_jawab' => 'Tamu Sinkron',
            'jumlah_anggota' => 2,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => $today,
            'total_price' => 40000,
            'status' => 'paid',
        ]);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'ITH-SYNCOFFLINE01',
            'payment_method' => 'qris',
            'subtotal' => 40000,
            'total_amount' => 40000,
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->petugasUser)->postJson(route('petugas.scan.sync-offline'), [
            'items' => [
                [
                    'invoice_code' => 'ITH-SYNCOFFLINE01',
                    'scanned_at' => now()->toDateTimeString(),
                    'gate_incident_tag' => '⭐ VIP',
                    'gate_notes' => 'Tamu dinas pendidikan',
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'synced_count' => 1,
            'failed_count' => 0,
        ]);

        $transaction->refresh();
        $this->assertNotNull($transaction->used_at);
        $this->assertEquals('⭐ VIP', $transaction->gate_incident_tag);
        $this->assertEquals('Tamu dinas pendidikan', $transaction->gate_notes);
        $this->assertEquals($this->petugasUser->id, $transaction->scanned_by_user_id);

        $this->assertDatabaseHas('qr_scan_logs', [
            'transaction_id' => $transaction->id,
            'scanned_by' => $this->petugasUser->id,
            'gate_incident_tag' => '⭐ VIP',
            'gate_notes' => 'Tamu dinas pendidikan',
            'status' => 'success',
        ]);
    }

    /**
     * Fitur E Petugas: Petugas dapat menambahkan catatan gate dan tag insiden saat validasi tiket.
     */
    public function test_officer_can_add_gate_notes_and_incident_tags(): void
    {
        $today = now()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museum->id,
            'nama_penanggung_jawab' => 'Pengunjung Difabel',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => $today,
            'total_price' => 20000,
            'status' => 'paid',
        ]);

        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'ITH-DIFABEL01',
            'payment_method' => 'cash',
            'subtotal' => 20000,
            'total_amount' => 20000,
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($this->petugasUser)->post(route('petugas.qrcodes.validate'), [
            'invoice_code' => 'ITH-DIFABEL01',
            'gate_incident_tag' => '♿ Kursi Roda / Difabel',
            'gate_notes' => 'Pengunjung membutuhkan jalur rampa dan pendamping museum.',
        ]);

        $response->assertSessionHas('success');
        $transaction->refresh();

        $this->assertEquals('♿ Kursi Roda / Difabel', $transaction->gate_incident_tag);
        $this->assertEquals('Pengunjung membutuhkan jalur rampa dan pendamping museum.', $transaction->gate_notes);
        $this->assertEquals($this->petugasUser->id, $transaction->scanned_by_user_id);
    }

    /**
     * Fitur D Petugas: Metrik shift kerja petugas terhitung dengan akurat.
     */
    public function test_officer_shift_metrics_are_calculated_correctly(): void
    {
        $today = now()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museum->id,
            'nama_penanggung_jawab' => 'Tamu Shift',
            'jumlah_anggota' => 3,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => $today,
            'total_price' => 60000,
            'status' => 'paid',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'ITH-SHIFT01',
            'payment_method' => 'cash',
            'subtotal' => 60000,
            'total_amount' => 60000,
            'payment_status' => 'paid',
        ]);

        $this->actingAs($this->petugasUser)->post(route('petugas.qrcodes.validate'), [
            'invoice_code' => 'ITH-SHIFT01',
        ]);

        $response = $this->actingAs($this->petugasUser)->get(route('petugas.qrcodes.scan'));
        $response->assertStatus(200);
        $response->assertSee('1 Tiket');
        $response->assertSee('3 Orang');
    }
}
