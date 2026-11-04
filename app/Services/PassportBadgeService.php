<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PassportBadgeService
{
    /**
     * Build digital passport and explorer badges for the given user.
     *
     * @return array<string, mixed>
     */
    public function getPassportData(User $user): array
    {
        // 1. Get all visited bookings (paid + used_at is not null)
        $visitedTransactions = Transaction::with(['booking.museum', 'booking.review'])
            ->whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('payment_status', 'paid')
            ->whereNotNull('used_at')
            ->latest('used_at')
            ->get();

        // 2. Generate stamps with authentic vintage postmark aesthetics
        $stamps = [];
        $visitedMuseumIds = [];
        $visitedCategories = [];

        $rotations = [-3.5, 2.5, -2.0, 3.2, -1.5, 2.8, -3.0, 1.8];
        $stampColorPalettes = [
            [
                'border' => '#991B1B',
                'bg' => '#FEF2F2',
                'text' => '#7F1D1D',
                'ring' => '#B91C1C',
                'badge' => 'bg-red-50 text-red-700 border-red-200',
            ],
            [
                'border' => '#1E3A8A',
                'bg' => '#EFF6FF',
                'text' => '#172554',
                'ring' => '#1D4ED8',
                'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
            ],
            [
                'border' => '#065F46',
                'bg' => '#ECFDF5',
                'text' => '#022C22',
                'ring' => '#047857',
                'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            ],
            [
                'border' => '#854D0E',
                'bg' => '#FEFCE8',
                'text' => '#713F12',
                'ring' => '#A16207',
                'badge' => 'bg-amber-50 text-amber-800 border-amber-200',
            ],
            [
                'border' => '#581C87',
                'bg' => '#FAF5FF',
                'text' => '#3B0764',
                'ring' => '#7E22CE',
                'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
            ],
        ];

        foreach ($visitedTransactions as $index => $tx) {
            $booking = $tx->booking;
            $museum = $booking->museum ?? null;

            if (! $museum) {
                continue;
            }

            $visitedMuseumIds[] = $museum->id;
            if (! empty($museum->category)) {
                $visitedCategories[] = strtolower($museum->category);
            }
            if (Str::contains(strtolower($museum->name), ['seni', 'barli', 'art'])) {
                $visitedCategories[] = 'seni';
            }
            if (Str::contains(strtolower($museum->name), ['geologi', 'sains', 'alam'])) {
                $visitedCategories[] = 'alam';
            }

            $stampDate = $tx->used_at ? Carbon::parse($tx->used_at) : ($booking->visit_date ? Carbon::parse($booking->visit_date) : now());
            $city = $museum->address ?: 'Nusantara';
            if (preg_match('/(Jakarta|Bandung|Yogyakarta|Jogja|Solo|Surakarta|Surabaya|Semarang|Denpasar|Bali|Bogor|Malang|Medan|Padang|Aceh|Makassar)/i', $city, $matches)) {
                $city = ucfirst(strtolower($matches[1]));
            }

            $palette = $stampColorPalettes[$museum->id % count($stampColorPalettes)];
            $rotation = $rotations[$index % count($rotations)];

            $stamps[] = [
                'id' => $tx->id,
                'museum_id' => $museum->id,
                'museum_name' => $museum->name,
                'museum_image' => $museum->image,
                'city' => $city,
                'date' => $stampDate->translatedFormat('d M Y'),
                'time' => $stampDate->format('H:i'),
                'stamp_code' => 'ITH-'.strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $museum->name), 0, 3)).'-'.$tx->id,
                'color' => $palette['border'],
                'color_bg' => $palette['bg'],
                'color_text' => $palette['text'],
                'color_ring' => $palette['ring'],
                'badge_class' => $palette['badge'],
                'rotation' => $rotation,
                'pax' => ($booking->adult_qty ?? 0) + ($booking->student_qty ?? 0) + ($booking->child_qty ?? 0),
                'review' => $booking->review,
            ];
        }

        $distinctMuseumCount = count(array_unique($visitedMuseumIds));
        $totalVisits = count($stamps);

        // 3. Check review with photo count
        $reviewWithPhotosCount = Review::where('user_id', $user->id)
            ->whereNotNull('photos')
            ->count();

        // 4. Calculate Badges with Real World Incentives & Perks
        $badges = [
            [
                'id' => 'perintis',
                'name' => 'Perintis Budaya',
                'category' => 'Eksplorasi',
                'icon' => '🏆',
                'tier' => 'Bronze',
                'description' => 'Telah melakukan kunjungan ke museum pertamamu di Itihasa.',
                'is_unlocked' => $distinctMuseumCount >= 1,
                'current' => min($distinctMuseumCount, 1),
                'target' => 1,
                'progress_percent' => min(round(($distinctMuseumCount / 1) * 100), 100),
                'reward_badge' => 'Diskon 10% + Pin Fisik Loket',
                'promo_code' => 'PERINTIS10',
                'discount_percent' => 10,
                'reward_title' => 'Voucher Diskon 10% & Pin Fisik Kolektor',
                'reward_description' => 'Dapatkan potongan 10% untuk pemesanan tiket museum berikutnya di ekosistem Itihasa, serta klaim Pin Enamel Eksklusif Kolektor Perintis di loket!',
                'physical_perk' => 'Pin Enamel Eksklusif Kolektor Budaya di Loket',
                'how_to_claim' => 'Gunakan kode PERINTIS10 saat checkout berikutnya, dan tunjukkan lencana ini ke petugas loket untuk mengambil pin gratis.',
            ],
            [
                'id' => 'penjelajah',
                'name' => 'Penjelajah Sejarah',
                'category' => 'Eksplorasi',
                'icon' => '🏛️',
                'tier' => 'Silver',
                'description' => 'Menjelajahi 3 atau lebih museum bersejarah di Indonesia.',
                'is_unlocked' => $distinctMuseumCount >= 3,
                'current' => min($distinctMuseumCount, 3),
                'target' => 3,
                'progress_percent' => min(round(($distinctMuseumCount / 3) * 100), 100),
                'reward_badge' => 'Diskon 15% + Kartu Pos Budaya',
                'promo_code' => 'JELAJAH15',
                'discount_percent' => 15,
                'reward_title' => 'Voucher Diskon 15% & Set Kartu Pos Seni',
                'reward_description' => 'Potongan harga 15% untuk pemesanan tiket individu maupun rombongan, ditambah Set Kartu Pos Seni Koleksi Terbatas di loket museum mitra.',
                'physical_perk' => 'Set Kartu Pos Seni & Sejarah Nusantara di Loket',
                'how_to_claim' => 'Salin kode promo JELAJAH15 di checkout, atau perlihatkan profil terverifikasi ini di meja informasi museum.',
            ],
            [
                'id' => 'seni_galeri',
                'name' => 'Apresiator Seni',
                'category' => 'Tematik',
                'icon' => '🎨',
                'tier' => 'Gold',
                'description' => 'Mengunjungi museum bertema Seni, Galeri, atau Budaya.',
                'is_unlocked' => in_array('seni', $visitedCategories) || in_array('budaya', $visitedCategories),
                'current' => (in_array('seni', $visitedCategories) || in_array('budaya', $visitedCategories)) ? 1 : 0,
                'target' => 1,
                'progress_percent' => (in_array('seni', $visitedCategories) || in_array('budaya', $visitedCategories)) ? 100 : 0,
                'reward_badge' => 'Diskon 20% + E-Booklet Kurator',
                'promo_code' => 'SENI20',
                'discount_percent' => 20,
                'reward_title' => 'Voucher Galeri Seni 20% & Booklet Kuratorial',
                'reward_description' => 'Potongan 20% khusus museum galeri & seni, serta hak akses digital eksklusif ke katalog kuratorial karya agung museum seni mitra.',
                'physical_perk' => 'Akses Booklet Kurator Karya Seni & Bookmark Eksklusif',
                'how_to_claim' => 'Gunakan kode kupon SENI20 saat melakukan reservasi ke museum seni atau galeri budaya.',
            ],
            [
                'id' => 'geologi_sains',
                'name' => 'Pecinta Sains & Bumi',
                'category' => 'Tematik',
                'icon' => '🌋',
                'tier' => 'Gold',
                'description' => 'Mengunjungi museum bertema Alam, Sains, atau Geologi.',
                'is_unlocked' => in_array('alam', $visitedCategories) || in_array('sains', $visitedCategories),
                'current' => (in_array('alam', $visitedCategories) || in_array('sains', $visitedCategories)) ? 1 : 0,
                'target' => 1,
                'progress_percent' => (in_array('alam', $visitedCategories) || in_array('sains', $visitedCategories)) ? 100 : 0,
                'reward_badge' => 'Diskon 20% + Stiker Hologram',
                'promo_code' => 'SAINS20',
                'discount_percent' => 20,
                'reward_title' => 'Voucher Sains 20% & Stiker Hologram Ekspedisi',
                'reward_description' => 'Diskon 20% tiket museum sains & geologi, serta stiker hologram edisi ekspedisi bumi resmi dari Itihasa di loket.',
                'physical_perk' => 'Stiker Hologram Ekspedisi Geologi Resmi di Loket',
                'how_to_claim' => 'Terapkan kupon SAINS20 saat pemesanan museum sains, dan klaim stiker saat validasi gate di lokasi.',
            ],
            [
                'id' => 'mahapatih',
                'name' => 'Mahapatih Nusantara',
                'category' => 'Mastery',
                'icon' => '👑',
                'tier' => 'Diamond',
                'description' => 'Menjelajahi 5 atau lebih destinasi museum di seluruh Indonesia.',
                'is_unlocked' => $distinctMuseumCount >= 5,
                'current' => min($distinctMuseumCount, 5),
                'target' => 5,
                'progress_percent' => min(round(($distinctMuseumCount / 5) * 100), 100),
                'reward_badge' => 'Diskon 25% + Jalur VIP Fast-Track Gate',
                'promo_code' => 'MAHAPATIH25',
                'discount_percent' => 25,
                'reward_title' => 'Diskon 25% Seumur Hidup & Jalur Bebas Antre VIP',
                'reward_description' => 'Gelar kehormatan tertinggi: Diskon permanen 25%, prioritas jalur cepat (VIP Fast-Track) bebas antre di gate loket, dan Medali Pin Emas Mahapatih!',
                'physical_perk' => 'Jalur Cepat Fast-Track Loket + Medali Pin Emas Mahapatih',
                'how_to_claim' => 'Ketik MAHAPATIH25 saat checkout, dan tunjukkan stempel emas di gate untuk langsung melewati antrean pengunjung reguler.',
            ],
            [
                'id' => 'kurator_foto',
                'name' => 'Kurator Visual',
                'category' => 'Kontribusi',
                'icon' => '📸',
                'tier' => 'Silver',
                'description' => 'Menulis ulasan museum dengan melampirkan foto pengalaman asli.',
                'is_unlocked' => $reviewWithPhotosCount >= 1,
                'current' => min($reviewWithPhotosCount, 1),
                'target' => 1,
                'progress_percent' => min(round(($reviewWithPhotosCount / 1) * 100), 100),
                'reward_badge' => 'Diskon 10% + Pin Verified Reviewer',
                'promo_code' => 'ULASAN10',
                'discount_percent' => 10,
                'reward_title' => 'Voucher 10% & Pin Kontributor Resmi',
                'reward_description' => 'Penghargaan atas foto otentikmu: Dapatkan diskon 10% untuk tiket berikutnya serta lencana Terverifikasi di kolom ulasan publik.',
                'physical_perk' => 'Pin Kontributor Komunitas Budaya di Loket Mitra',
                'how_to_claim' => 'Gunakan kode ULASAN10 pada pesanan tiketmu berikutnya.',
            ],
        ];

        $unlockedBadgesCount = count(array_filter($badges, fn ($b) => $b['is_unlocked']));

        // 5. Explorer Rank & Progression Perks
        if ($distinctMuseumCount >= 5) {
            $rankName = 'Mahapatih Budaya';
            $rankLevel = 4;
            $nextTarget = null;
            $rankPerk = 'VIP Fast-Track Gate + Sertifikat Emas Nasional + Diskon 25%';
        } elseif ($distinctMuseumCount >= 3) {
            $rankName = 'Penjelajah Madya';
            $rankLevel = 3;
            $nextTarget = 5;
            $rankPerk = 'Set Kartu Pos Budaya + Sertifikat Penjelajah + Diskon 15%';
        } elseif ($distinctMuseumCount >= 1) {
            $rankName = 'Wisatawan Muda';
            $rankLevel = 2;
            $nextTarget = 3;
            $rankPerk = 'Pin Kolektor Perintis + Kupon Diskon 10%';
        } else {
            $rankName = 'Perintis Pemula';
            $rankLevel = 1;
            $nextTarget = 1;
            $rankPerk = 'Kunjungi 1 museum pertamamu untuk membuka Pin Kolektor & Diskon 10%!';
        }

        // Calculate empty slots to complete the current passport spread (minimum 6 stamps per sheet)
        $sheetCapacity = max(6, (int) ceil(max(count($stamps), 1) / 3) * 3);
        $emptySlotsCount = max(1, min(3, $sheetCapacity - count($stamps)));

        // Ensure badge reward promo codes are registered in the system
        $this->ensureBadgePromos();

        return [
            'stamps' => $stamps,
            'empty_slots_count' => $emptySlotsCount,
            'badges' => $badges,
            'total_stamps' => $totalVisits,
            'distinct_museums' => $distinctMuseumCount,
            'unlocked_badges_count' => $unlockedBadgesCount,
            'total_badges' => count($badges),
            'rank_name' => $rankName,
            'rank_level' => $rankLevel,
            'rank_perk' => $rankPerk,
            'next_target' => $nextTarget,
            'passport_serial' => 'ITH-PASSPORT-'.str_pad($user->id, 6, '0', STR_PAD_LEFT),
            'certificate_number' => 'ITH-CERT-'.date('Y').'-'.str_pad($user->id, 5, '0', STR_PAD_LEFT),
        ];
    }

    /**
     * Gracefully ensure that the ecosystem promo codes tied to badges exist in the database.
     */
    protected function ensureBadgePromos(): void
    {
        try {
            $promosToSeed = [
                [
                    'code' => 'PERINTIS10',
                    'title' => 'Hadiah Lencana Perintis Budaya - Diskon 10%',
                    'discount_value' => 10,
                ],
                [
                    'code' => 'JELAJAH15',
                    'title' => 'Hadiah Lencana Penjelajah Sejarah - Diskon 15%',
                    'discount_value' => 15,
                ],
                [
                    'code' => 'SENI20',
                    'title' => 'Hadiah Lencana Apresiator Seni - Diskon 20%',
                    'discount_value' => 20,
                ],
                [
                    'code' => 'SAINS20',
                    'title' => 'Hadiah Lencana Pecinta Sains & Bumi - Diskon 20%',
                    'discount_value' => 20,
                ],
                [
                    'code' => 'MAHAPATIH25',
                    'title' => 'Hadiah Lencana Mahapatih Nusantara - Diskon 25%',
                    'discount_value' => 25,
                ],
                [
                    'code' => 'ULASAN10',
                    'title' => 'Hadiah Lencana Kurator Visual - Diskon 10%',
                    'discount_value' => 10,
                ],
            ];

            foreach ($promosToSeed as $data) {
                Promo::firstOrCreate(
                    ['code' => $data['code']],
                    [
                        'museum_id' => null,
                        'title' => $data['title'],
                        'discount_type' => 'percent',
                        'discount_value' => $data['discount_value'],
                        'min_total_price' => 0,
                        'max_discount' => 100000,
                        'quota' => 999999,
                        'used_count' => 0,
                        'start_date' => now()->subDay()->toDateString(),
                        'end_date' => now()->addYears(10)->toDateString(),
                        'is_active' => true,
                    ]
                );
            }
        } catch (\Throwable $e) {
            // Silently skip if database connection or schema is unavailable
        }
    }
}
