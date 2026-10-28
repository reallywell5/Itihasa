<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'museum_id',
        'booking_id',
        'rating',
        'comment',
        'photos',
        'admin_reply',
        'admin_replied_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'admin_replied_at' => 'datetime',
        ];
    }

    /**
     * Get public URLs of uploaded review photos.
     *
     * @return array<int, string>
     */
    public function photoUrls(): array
    {
        if (empty($this->photos) || ! is_array($this->photos)) {
            return [];
        }

        return array_map(function ($photo) {
            if (Str::startsWith($photo, ['http://', 'https://'])) {
                return $photo;
            }
            if (Str::startsWith($photo, 'storage/')) {
                return asset($photo);
            }

            return asset('storage/'.$photo);
        }, $this->photos);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reports()
    {
        return $this->hasMany(ReviewReport::class);
    }
}
