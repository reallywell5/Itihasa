<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Museum;
use App\Models\MuseumSpecialDate;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFeaturesBCDEFTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    private User $regularUser;

    private Museum $museum;

    private Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->museum = Museum::create([
            'name' => 'Museum Bahari Test',
            'category' => 'museum',
            'address' => 'Jl. Pasar Ikan No. 1, Jakarta Utara',
            'description' => 'Museum sejarah kelautan nusantara.',
            'opening_time' => '08:00',
            'closing_time' => '16:00',
            'operational_hours' => [
                'senin' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
                'selasa' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
                'rabu' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
                'kamis' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
                'jumat' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
                'sabtu' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
                'minggu' => ['closed' => false, 'sessions' => [['open' => '08:00', 'close' => '16:00']]],
            ],
            'facilities' => ['Mushola', 'Area Parkir', 'Toilet Bersih'],
        ]);

        $this->ticket = Ticket::create([
            'museum_id' => $this->museum->id,
            'ticket_name' => 'Tiket Dewasa',
            'price' => 50000,
            'slot' => 100,
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin Bahari',
            'email' => 'adminbahari@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'museum_id' => $this->museum->id,
        ]);

        $this->regularUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budisantoso@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);
    }

    /**
     * Fitur B Admin: Admin Museum dapat membalas ulasan dan tampil sebagai respon resmi di halaman detail.
     */
    public function test_admin_museum_can_reply_to_review_and_displayed_on_public_page(): void
    {
        $booking = Booking::create([
            'user_id' => $this->regularUser->id,
            'museum_id' => $this->museum->id,
            'nama_penanggung_jawab' => 'Budi Santoso',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'visit_date' => now()->toDateString(),
            'total_price' => 50000,
            'status' => 'paid',
        ]);

        $review = Review::create([
            'user_id' => $this->regularUser->id,
            'museum_id' => $this->museum->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'comment' => 'Koleksi perahunya sangat megah dan terawat!',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.reviews.reply', $review->id), [
            'admin_reply' => 'Terima kasih banyak atas kunjungannya, kami tunggu kedatangan Anda berikutnya!',
        ]);

        $response->assertRedirect(route('admin.reviews.index'));
        $review->refresh();
        $this->assertEquals('Terima kasih banyak atas kunjungannya, kami tunggu kedatangan Anda berikutnya!', $review->admin_reply);
        $this->assertNotNull($review->admin_replied_at);

        // Cek tampil di halaman publik detail museum
        $detailResponse = $this->get(route('museum.detail', $this->museum->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Respon Resmi Pengelola');
        $detailResponse->assertSee('Terima kasih banyak atas kunjungannya');
    }

    /**
     * Fitur C Admin: Admin Museum dapat membuat tanggal libur khusus (blackout date), dan user dilarang booking.
     */
    public function test_admin_museum_can_set_special_blackout_date_and_blocks_booking(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $response = $this->actingAs($this->adminUser)->post(route('admin.special-dates.store'), [
            'date' => $tomorrow,
            'is_closed' => 1,
            'notes' => 'Renovasi Gedung Utama',
        ]);

        $response->assertRedirect(route('admin.special-dates.index'));
        $specialDate = MuseumSpecialDate::where('museum_id', $this->museum->id)
            ->whereDate('date', $tomorrow)
            ->first();

        $this->assertNotNull($specialDate);
        $this->assertTrue((bool) $specialDate->is_closed);
        $this->assertEquals('Renovasi Gedung Utama', $specialDate->notes);

        $this->assertTrue($this->museum->fresh()->isClosedOnDate($tomorrow));

        // User mencoba booking di tanggal libur khusus tersebut
        $bookResponse = $this->actingAs($this->regularUser)->post(route('user.booking.store', $this->museum->id), [
            'visit_date' => $tomorrow,
            'nama_penanggung_jawab' => 'Budi Santoso',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'ticket_'.$this->ticket->id => 1,
        ]);

        $bookResponse->assertSessionHas('error');
    }

    /**
     * Fitur E Admin: Admin Museum dapat membuat kupon promo dan user mendapatkan potongan harga saat booking.
     */
    public function test_admin_museum_can_create_promo_and_user_gets_discount(): void
    {
        $promoCode = 'MERDEKA20';

        $response = $this->actingAs($this->adminUser)->post(route('admin.promos.store'), [
            'code' => $promoCode,
            'title' => 'Promo Diskon 20%',
            'discount_type' => 'percent',
            'discount_value' => 20,
            'min_total_price' => 50000,
            'quota' => 50,
            'start_date' => Carbon::today()->toDateString(),
            'end_date' => Carbon::today()->addDays(10)->toDateString(),
        ]);

        $response->assertRedirect(route('admin.promos.index'));
        $this->assertDatabaseHas('promos', [
            'code' => $promoCode,
            'discount_value' => 20,
            'discount_type' => 'percent',
        ]);

        // Cek endpoint check-promo
        $checkPromo = $this->actingAs($this->regularUser)->postJson(route('user.booking.check-promo', $this->museum->id), [
            'promo_code' => $promoCode,
            'total_price' => 100000,
            'visit_date' => Carbon::tomorrow()->toDateString(),
        ]);

        $checkPromo->assertStatus(200);
        $checkPromo->assertJson([
            'valid' => true,
            'code' => $promoCode,
            'discount' => 20000,
            'final_total' => 80000,
        ]);

        // Booking dengan promo
        $bookResponse = $this->actingAs($this->regularUser)->post(route('user.booking.store', $this->museum->id), [
            'visit_date' => Carbon::tomorrow()->toDateString(),
            'nama_penanggung_jawab' => 'Rian Hidayat',
            'jumlah_anggota' => 2,
            'kota_asal' => 'Bandung',
            'no_hp' => '081234567890',
            'promo_code' => $promoCode,
            'ticket_'.$this->ticket->id => 2, // 2 x 50.000 = 100.000, diskon 20.000 -> 80.000
        ]);

        $booking = Booking::where('user_id', $this->regularUser->id)->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(80000, $booking->total_price);
        $this->assertEquals(20000, $booking->discount_amount);
        $this->assertEquals($promoCode, $booking->promo_code);
    }

    /**
     * Fitur D Admin: Admin Museum dapat melihat rekap tutup kas harian & mengunduh CSV.
     */
    public function test_admin_museum_can_view_daily_cash_recap_and_export_csv(): void
    {
        $today = Carbon::today()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->regularUser->id,
            'nama_penanggung_jawab' => 'Agus Loket',
            'jumlah_anggota' => 2,
            'kota_asal' => 'Jakarta',
            'no_hp' => '081234567890',
            'museum_id' => $this->museum->id,
            'visit_date' => $today,
            'adult_qty' => 2,
            'student_qty' => 0,
            'child_qty' => 0,
            'total_price' => 100000,
            'status' => 'paid',
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'ITH-REKAPTEST',
            'payment_method' => 'cash',
            'subtotal' => 100000,
            'total_amount' => 100000,
            'payment_status' => 'paid',
            'created_at' => now(),
        ]);

        $rekapResponse = $this->actingAs($this->adminUser)->get(route('admin.transactions.rekap', ['date' => $today]));
        $rekapResponse->assertStatus(200);
        $rekapResponse->assertSee('ITH-REKAPTEST');
        $rekapResponse->assertSee('Agus Loket');
        $rekapResponse->assertSee('100.000');

        $exportResponse = $this->actingAs($this->adminUser)->get(route('admin.transactions.export', ['date' => $today]));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    /**
     * Fitur F Admin: Admin Museum dapat mengelola profil, fasilitas, dan jadwal operasionalnya secara mandiri.
     */
    public function test_admin_museum_can_update_own_profile_and_facilities(): void
    {
        $response = $this->actingAs($this->adminUser)->put(route('admin.museum.profile.update'), [
            'name' => 'Museum Bahari Jaya Terpadu',
            'category' => 'museum',
            'address' => 'Jl. Pasar Ikan No. 10, Sunda Kelapa, Jakarta',
            'description' => 'Museum bahari terbesar dengan koleksi pinisi nusantara.',
            'facilities' => ['Mushola Nyaman', 'Area Parkir Luas', 'Audio Guide Digital', 'Kafe Bahari'],
            'operational_hours' => [
                'senin' => ['closed' => true, 'sessions' => []],
                'selasa' => ['closed' => false, 'sessions' => [['open' => '09:00', 'close' => '17:00']]],
                'rabu' => ['closed' => false, 'sessions' => [['open' => '09:00', 'close' => '17:00']]],
                'kamis' => ['closed' => false, 'sessions' => [['open' => '09:00', 'close' => '17:00']]],
                'jumat' => ['closed' => false, 'sessions' => [['open' => '09:00', 'close' => '17:00']]],
                'sabtu' => ['closed' => false, 'sessions' => [['open' => '09:00', 'close' => '17:00']]],
                'minggu' => ['closed' => false, 'sessions' => [['open' => '09:00', 'close' => '17:00']]],
            ],
        ]);

        $response->assertRedirect(route('admin.museum.profile.edit'));
        $this->museum->refresh();
        $this->assertEquals('Museum Bahari Jaya Terpadu', $this->museum->name);
        $this->assertContains('Kafe Bahari', $this->museum->facilities);
        $this->assertTrue($this->museum->operational_hours['senin']['closed']);
    }
}
