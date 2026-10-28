<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Broadcast;
use App\Models\Museum;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminFeaturesACETest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $adminUser;

    private User $petugasUser;

    private User $visitorUser;

    private Museum $museumA;

    private Museum $museumB;

    private Ticket $ticketA;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-08-16 10:00:00');

        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);

        $this->museumA = Museum::create([
            'name' => 'Museum Nasional Jakarta',
            'category' => 'museum',
            'status' => 'active',
            'address' => 'Jl. Medan Merdeka Barat No. 12',
            'description' => 'Museum gajah bersejarah di Jakarta Pusat.',
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
            'facilities' => ['Mushola', 'Toilet', 'WiFi'],
        ]);

        $this->museumB = Museum::create([
            'name' => 'Museum Bahari Sunda Kelapa',
            'category' => 'museum',
            'status' => 'active',
            'address' => 'Jl. Pasar Ikan No. 1',
            'description' => 'Museum kebaharian nusantara.',
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
            'facilities' => ['Parkir'],
        ]);

        $this->ticketA = Ticket::create([
            'museum_id' => $this->museumA->id,
            'ticket_name' => 'Tiket Reguler Dewasa',
            'price' => 25000,
            'slot' => 100,
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin Museum A',
            'email' => 'adminA@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'museum_id' => $this->museumA->id,
        ]);

        $this->petugasUser = User::create([
            'name' => 'Petugas Gate A',
            'email' => 'staffA@test.com',
            'password' => bcrypt('password123'),
            'role' => 'staff',
            'museum_id' => $this->museumA->id,
        ]);

        $this->visitorUser = User::create([
            'name' => 'Pengunjung Wisata',
            'email' => 'visitor@test.com',
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
     * Fitur A Super Admin: Filter rentang waktu fleksibel pada dashboard monitoring (Hari ini vs custom date range).
     */
    public function test_super_admin_can_filter_dashboard_by_flexible_date_ranges(): void
    {
        // Transaksi Hari Ini (16 Agustus 2026)
        $bookingToday = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museumA->id,
            'nama_penanggung_jawab' => 'Tamu Hari Ini',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => '2026-08-16',
            'total_price' => 50000,
            'status' => 'paid',
        ]);

        Transaction::create([
            'booking_id' => $bookingToday->id,
            'invoice_code' => 'ITH-TODAY01',
            'payment_method' => 'qris',
            'subtotal' => 50000,
            'total_amount' => 50000,
            'payment_status' => 'paid',
            'created_at' => Carbon::parse('2026-08-16 09:00:00'),
        ]);

        // Transaksi Bulan Lalu (10 Juli 2026)
        $bookingPast = Booking::create([
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museumA->id,
            'nama_penanggung_jawab' => 'Tamu Bulan Lalu',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => '2026-07-10',
            'total_price' => 100000,
            'status' => 'paid',
        ]);

        $txPast = Transaction::create([
            'booking_id' => $bookingPast->id,
            'invoice_code' => 'ITH-PAST01',
            'payment_method' => 'qris',
            'subtotal' => 100000,
            'total_amount' => 100000,
            'payment_status' => 'paid',
        ]);
        $txPast->created_at = Carbon::parse('2026-07-10 14:00:00');
        $txPast->save();

        // 1. Filter Hari Ini -> Menghitung omzet hari ini (Rp 50.000)
        $responseToday = $this->actingAs($this->superAdmin)->get(route('admin.dashboard', ['period' => 'today']));
        $responseToday->assertStatus(200);
        $responseToday->assertViewHas('totalRevenue', 50000);
        $responseToday->assertViewHas('paidTransactions', 1);

        // 2. Filter Semua Waktu -> Menghitung total semua transaksi (Rp 150.000)
        $responseAll = $this->actingAs($this->superAdmin)->get(route('admin.dashboard', ['period' => 'all']));
        $responseAll->assertStatus(200);
        $responseAll->assertViewHas('totalRevenue', 150000);
        $responseAll->assertViewHas('paidTransactions', 2);

        // 3. Filter Custom Date Range (Juli 2026)
        $responseCustom = $this->actingAs($this->superAdmin)->get(route('admin.dashboard', [
            'period' => 'custom',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-31',
        ]));
        $responseCustom->assertStatus(200);
        $responseCustom->assertViewHas('totalRevenue', 100000);
        $responseCustom->assertViewHas('paidTransactions', 1);
    }

    /**
     * Fitur C Super Admin: Super Admin dapat mengubah status kemitraan & operasional museum.
     */
    public function test_super_admin_can_update_museum_operational_status(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.museums.status', $this->museumA->id), [
            'status' => 'renovation',
        ]);

        $response->assertSessionHas('success');
        $this->museumA->refresh();

        $this->assertEquals('renovation', $this->museumA->status);
        $this->assertTrue($this->museumA->isUnderRenovation());
        $this->assertFalse($this->museumA->isActive());

        // Ubah menjadi inactive
        $response2 = $this->actingAs($this->superAdmin)->post(route('admin.museums.status', $this->museumA->id), [
            'status' => 'inactive',
        ]);

        $response2->assertSessionHas('success');
        $this->museumA->refresh();

        $this->assertEquals('inactive', $this->museumA->status);
        $this->assertTrue($this->museumA->isInactive());
    }

    /**
     * Fitur C Super Admin: Pengunjung tidak dapat memesan tiket jika museum dalam status renovasi atau nonaktif.
     */
    public function test_visitors_cannot_book_tickets_for_museum_under_renovation_or_inactive(): void
    {
        $this->museumA->update(['status' => 'renovation']);

        $response = $this->actingAs($this->visitorUser)->post(route('user.booking.store', $this->museumA->id), [
            'nama_penanggung_jawab' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'kota_asal' => 'Jakarta',
            'visit_date' => '2026-08-16',
            'tickets' => [
                $this->ticketA->id => 2,
            ],
            'jumlah_anggota' => 2,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $this->visitorUser->id,
            'museum_id' => $this->museumA->id,
        ]);
    }

    /**
     * Fitur E Super Admin: Super Admin dapat membuat broadcast pengumuman resmi dan toggle status.
     */
    public function test_super_admin_can_create_broadcast_announcements_and_toggle_status(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.broadcasts.store'), [
            'title' => 'Instruksi Pengamanan Libur Nasional',
            'message' => 'Seluruh petugas gate wajib memastikan scan tiket terverifikasi dan pengunjung mengenakan ID badge.',
            'urgency' => 'warning',
            'target_role' => 'all',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.broadcasts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('broadcasts', [
            'title' => 'Instruksi Pengamanan Libur Nasional',
            'urgency' => 'warning',
            'target_role' => 'all',
            'is_active' => true,
        ]);

        $broadcast = Broadcast::where('title', 'Instruksi Pengamanan Libur Nasional')->first();

        // Toggle status nonaktif
        $toggleRes = $this->actingAs($this->superAdmin)->post(route('admin.broadcasts.toggle', $broadcast->id));
        $toggleRes->assertSessionHas('success');

        $broadcast->refresh();
        $this->assertFalse($broadcast->is_active);
    }

    /**
     * Fitur E Super Admin: Pengumuman aktif tampil pada dashboard Admin Museum dan Scanner Petugas.
     */
    public function test_active_broadcasts_appear_on_admin_and_petugas_dashboards(): void
    {
        Broadcast::create([
            'created_by_user_id' => $this->superAdmin->id,
            'title' => 'Pemberitahuan Audit Operasional Bulanan',
            'message' => 'Laporan rekapitulasi kas harian bulan ini wajib disetor sebelum akhir pekan.',
            'urgency' => 'danger',
            'target_role' => 'all',
            'is_active' => true,
        ]);

        // Tampil di Dashboard Admin Museum
        $responseAdmin = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Pemberitahuan Audit Operasional Bulanan');
        $responseAdmin->assertSee('Laporan rekapitulasi kas harian bulan ini');

        // Tampil di Scanner Petugas Gate
        $responseStaff = $this->actingAs($this->petugasUser)->get(route('petugas.qrcodes.scan'));
        $responseStaff->assertStatus(200);
        $responseStaff->assertSee('Pemberitahuan Audit Operasional Bulanan');
    }
}
