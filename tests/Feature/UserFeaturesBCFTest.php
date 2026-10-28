<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Museum;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserFeaturesBCFTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_filter_museums_by_city_category_and_price_range(): void
    {
        $museum1 = Museum::create([
            'name' => 'Museum Barli Bandung',
            'address' => 'Bandung, Jawa Barat',
            'category' => 'seni',
            'description' => 'Museum seni lukis',
            'opening_time' => '08:00:00',
            'closing_time' => '17:00:00',
        ]);
        Ticket::create([
            'museum_id' => $museum1->id,
            'ticket_name' => 'Tiket Masuk',
            'price' => 20000,
            'slot' => 100,
        ]);

        $museum2 = Museum::create([
            'name' => 'Museum Nasional Jakarta',
            'address' => 'Jakarta Pusat',
            'category' => 'museum',
            'description' => 'Museum gajah nasional',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);
        Ticket::create([
            'museum_id' => $museum2->id,
            'ticket_name' => 'Tiket Masuk Gratis',
            'price' => 0,
            'slot' => 100,
        ]);

        // Filter city
        $responseCity = $this->get(route('user.home', ['city' => 'Bandung']));
        $responseCity->assertOk();
        $responseCity->assertSee('Museum Barli Bandung');
        $responseCity->assertDontSee('Museum Nasional Jakarta');

        // Filter category
        $responseCat = $this->get(route('user.home', ['category' => 'seni']));
        $responseCat->assertOk();
        $responseCat->assertSee('Museum Barli Bandung');
        $responseCat->assertDontSee('Museum Nasional Jakarta');

        // Filter price_range free
        $responsePrice = $this->get(route('user.home', ['price_range' => 'free']));
        $responsePrice->assertOk();
        $responsePrice->assertSee('Museum Nasional Jakarta');
        $responsePrice->assertDontSee('Museum Barli Bandung');
    }

    public function test_user_can_upload_photos_when_submitting_review(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Reviewer Asli',
            'email' => 'reviewer@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Geologi',
            'address' => 'Bandung',
            'category' => 'alam',
            'description' => 'Museum fosil dan mineral',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'museum_id' => $museum->id,
            'nama_penanggung_jawab' => 'Reviewer Asli',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Bandung',
            'no_hp' => '081234567890',
            'visit_date' => now()->toDateString(),
            'total_price' => 10000,
            'status' => 'active',
            'is_rombongan' => false,
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'INV-TEST-001',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'total_amount' => 10000,
            'payment_method' => 'qris',
            'used_at' => now(),
        ]);

        $photo1 = UploadedFile::fake()->image('experience1.jpg');
        $photo2 = UploadedFile::fake()->image('experience2.png');

        $response = $this->actingAs($user)->post(route('reviews.store', $booking->id), [
            'rating' => 5,
            'comment' => 'Pengalaman luar biasa dengan artefak memukau!',
            'photos' => [$photo1, $photo2],
        ]);

        $response->assertSessionHas('success');

        $review = Review::where('booking_id', $booking->id)->first();
        $this->assertNotNull($review);
        $this->assertEquals(5, $review->rating);
        $this->assertCount(2, $review->photos);
        $this->assertCount(2, $review->photoUrls());
    }

    public function test_user_profile_shows_passport_stamps_and_unlocked_badges(): void
    {
        $user = User::create([
            'name' => 'Penjelajah Museum',
            'email' => 'traveler@test.com',
            'password' => bcrypt('password123'),
            'role' => 'visitor',
        ]);

        $museum = Museum::create([
            'name' => 'Museum Geologi',
            'category' => 'alam',
            'address' => 'Bandung',
            'description' => 'Museum fosil',
            'opening_time' => '08:00:00',
            'closing_time' => '16:00:00',
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'museum_id' => $museum->id,
            'nama_penanggung_jawab' => 'Penjelajah Museum',
            'jumlah_anggota' => 1,
            'kota_asal' => 'Bandung',
            'no_hp' => '081234567890',
            'visit_date' => now()->toDateString(),
            'total_price' => 10000,
            'status' => 'active',
            'is_rombongan' => false,
        ]);

        Transaction::create([
            'booking_id' => $booking->id,
            'invoice_code' => 'INV-TEST-002',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'total_amount' => 10000,
            'payment_method' => 'qris',
            'used_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('user.profile'));
        $response->assertOk();
        $response->assertSee('PASPOR BUDAYA NUSANTARA');
        $response->assertSee('Perintis Budaya');
        $response->assertSee('Museum Geologi');
        $response->assertSee('Stempel');
    }
}
