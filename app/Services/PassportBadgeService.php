<?php

namespace App\Services;

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

        // 2. Generate stamps (grouped by museum for unique stamps, or all visited dates)
        $stamps = [];
        $visitedMuseumIds = [];
        $visitedCategories = [];

        foreach ($visitedTransactions as $tx) {
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
            // Extract short city
            if (preg_match('/(Jakarta|Bandung|Yogyakarta|Jogja|Solo|Surakarta|Surabaya|Semarang|Denpasar|Bali|Bogor|Malang)/i', $city, $matches)) {
                $city = ucfirst(strtolower($matches[1]));
            }

            $stampColors = ['#B88A44', '#102A43', '#8C6226', '#2E7D32', '#C0392B'];
            $color = $stampColors[$museum->id % count($stampColors)];

            $stamps[] = [
                'id' => $tx->id,
                'museum_id' => $museum->id,
                'museum_name' => $museum->name,
                'museum_image' => $museum->image,
                'city' => $city,
                'date' => $stampDate->translatedFormat('d M Y'),
                'time' => $stampDate->format('H:i'),
                'stamp_code' => 'ITH-'.strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $museum->name), 0, 3)).'-'.$tx->id,
                'color' => $color,
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

        // 4. Calculate Badges
        $badges = [
            [
                'id' => 'perintis',
                'name' => 'Perintis Budaya',
                'category' => 'Eksplorasi',
                'icon' => '🏆',
                'description' => 'Telah melakukan kunjungan ke museum pertamamu di Itihasa.',
                'is_unlocked' => $distinctMuseumCount >= 1,
                'current' => min($distinctMuseumCount, 1),
                'target' => 1,
                'progress_percent' => min(round(($distinctMuseumCount / 1) * 100), 100),
            ],
            [
                'id' => 'penjelajah',
                'name' => 'Penjelajah Sejarah',
                'category' => 'Eksplorasi',
                'icon' => '🏛️',
                'description' => 'Menjelajahi 3 atau lebih museum bersejarah di Indonesia.',
                'is_unlocked' => $distinctMuseumCount >= 3,
                'current' => min($distinctMuseumCount, 3),
                'target' => 3,
                'progress_percent' => min(round(($distinctMuseumCount / 3) * 100), 100),
            ],
            [
                'id' => 'seni_galeri',
                'name' => 'Apresiator Seni',
                'category' => 'Tematik',
                'icon' => '🎨',
                'description' => 'Mengunjungi museum bertema Seni, Galeri, atau Budaya.',
                'is_unlocked' => in_array('seni', $visitedCategories) || in_array('budaya', $visitedCategories),
                'current' => (in_array('seni', $visitedCategories) || in_array('budaya', $visitedCategories)) ? 1 : 0,
                'target' => 1,
                'progress_percent' => (in_array('seni', $visitedCategories) || in_array('budaya', $visitedCategories)) ? 100 : 0,
            ],
            [
                'id' => 'geologi_sains',
                'name' => 'Pecinta Sains & Bumi',
                'category' => 'Tematik',
                'icon' => '🌋',
                'description' => 'Mengunjungi museum bertema Alam, Sains, atau Geologi.',
                'is_unlocked' => in_array('alam', $visitedCategories) || in_array('sains', $visitedCategories),
                'current' => (in_array('alam', $visitedCategories) || in_array('sains', $visitedCategories)) ? 1 : 0,
                'target' => 1,
                'progress_percent' => (in_array('alam', $visitedCategories) || in_array('sains', $visitedCategories)) ? 100 : 0,
            ],
            [
                'id' => 'mahapatih',
                'name' => 'Mahapatih Nusantara',
                'category' => 'Mastery',
                'icon' => '👑',
                'description' => 'Menjelajahi 5 atau lebih destinasi museum di seluruh Indonesia.',
                'is_unlocked' => $distinctMuseumCount >= 5,
                'current' => min($distinctMuseumCount, 5),
                'target' => 5,
                'progress_percent' => min(round(($distinctMuseumCount / 5) * 100), 100),
            ],
            [
                'id' => 'kurator_foto',
                'name' => 'Kurator Visual',
                'category' => 'Kontribusi',
                'icon' => '📸',
                'description' => 'Menulis ulasan museum dengan melampirkan foto pengalaman asli.',
                'is_unlocked' => $reviewWithPhotosCount >= 1,
                'current' => min($reviewWithPhotosCount, 1),
                'target' => 1,
                'progress_percent' => min(round(($reviewWithPhotosCount / 1) * 100), 100),
            ],
        ];

        $unlockedBadgesCount = count(array_filter($badges, fn ($b) => $b['is_unlocked']));

        // 5. Explorer Rank
        if ($distinctMuseumCount >= 5) {
            $rankName = 'Mahapatih Budaya';
            $rankLevel = 4;
            $nextTarget = null;
        } elseif ($distinctMuseumCount >= 3) {
            $rankName = 'Penjelajah Madya';
            $rankLevel = 3;
            $nextTarget = 5;
        } elseif ($distinctMuseumCount >= 1) {
            $rankName = 'Wisatawan Muda';
            $rankLevel = 2;
            $nextTarget = 3;
        } else {
            $rankName = 'Perintis Pemula';
            $rankLevel = 1;
            $nextTarget = 1;
        }

        return [
            'stamps' => $stamps,
            'badges' => $badges,
            'total_stamps' => $totalVisits,
            'distinct_museums' => $distinctMuseumCount,
            'unlocked_badges_count' => $unlockedBadgesCount,
            'total_badges' => count($badges),
            'rank_name' => $rankName,
            'rank_level' => $rankLevel,
            'next_target' => $nextTarget,
        ];
    }
}
