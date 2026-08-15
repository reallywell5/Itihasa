<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'museum_id',
        'booking_id',
        'rating',
        'comment',
    ];

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
