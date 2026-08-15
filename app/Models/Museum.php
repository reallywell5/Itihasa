<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Museum extends Model
{
    use HasFactory;

    protected $table = 'museums';

    protected $fillable = [
        'name',
        'category',
        'address',
        'description',
        'image',
        'opening_time',
        'closing_time',
        'facilities',
        'operational_hours',
    ];

    protected $casts = [
        'facilities' => 'array',
        'operational_hours' => 'array',
    ];

    // Relasi
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function adminUser()
    {
        return $this->hasOne(User::class)->where('role', 'admin');
    }

    public function staffUsers()
    {
        return $this->hasMany(User::class)->where('role', 'staff');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function galleries()
    {
        return $this->hasMany(MuseumGallery::class)->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1) ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function lowestTicketPrice()
    {
        return $this->tickets()->min('price');
    }

    // === JADWAL OPERASIONAL PER HARI ===

    public static function dayKeys(): array
    {
        return ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
    }

    public static function dayLabels(): array
    {
        return [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
        ];
    }

    /**
     * Ambil jadwal (closed + sessions) untuk tanggal tertentu.
     */
    public function scheduleForDate($date): ?array
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        $dayKey = self::dayKeys()[$date->dayOfWeekIso - 1];

        return $this->operational_hours[$dayKey] ?? null;
    }

    /**
     * Apakah museum libur TOTAL di tanggal ini (dipakai untuk booking & badge Home,
     * tidak peduli jam istirahat di tengah hari).
     */
    public function isClosedOnDate($date): bool
    {
        $schedule = $this->scheduleForDate($date);

        // Data belum diisi sama sekali -> anggap libur demi keamanan (cegah booking ke jadwal tidak jelas)
        if (! $schedule) {
            return true;
        }

        return $schedule['closed'] ?? true;
    }

    /**
     * Daftar sesi jam buka di tanggal ini (kosong kalau libur).
     */
    public function sessionsForDate($date): array
    {
        $schedule = $this->scheduleForDate($date);

        if (! $schedule || ($schedule['closed'] ?? true)) {
            return [];
        }

        return $schedule['sessions'] ?? [];
    }

    /**
     * Rentang jam operasional keseluruhan di hari itu (dari sesi pertama buka
     * sampai sesi terakhir tutup) -- dipakai untuk badge Home & validasi booking
     * hari ini, supaya jam istirahat di tengah tetap dianggap "buka".
     */
    public function operatingBoundsForDate($date): ?array
    {
        $sessions = $this->sessionsForDate($date);

        if (empty($sessions)) {
            return null;
        }

        return [
            'open' => $sessions[0]['open'],
            'close' => end($sessions)['close'],
        ];
    }

    public function isOpenAt($date, ?string $time = null): bool
    {
        $time = $time ?? now()->format('H:i');

        foreach ($this->sessionsForDate($date) as $session) {
            if ($time >= $session['open'] && $time <= $session['close']) {
                return true;
            }
        }

        return false;
    }

    public function nextOpening($from = null): ?array
    {
        $from = $from ? Carbon::parse($from) : now();

        for ($i = 0; $i <= 7; $i++) {
            $date = $from->copy()->addDays($i);
            $bounds = $this->operatingBoundsForDate($date);

            if (! $bounds) {
                continue;
            }

            if ($i === 0) {
                // Hari ini: cuma valid kalau jam bukanya masih di depan (belum lewat)
                if ($from->format('H:i') < $bounds['open']) {
                    return ['date' => $date, 'time' => $bounds['open'], 'is_today' => true];
                }

                continue;
            }

            return ['date' => $date, 'time' => $bounds['open'], 'is_today' => false];
        }

        return null;
    }
}
