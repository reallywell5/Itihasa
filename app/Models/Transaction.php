<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id',
        'invoice_code',
        'payment_method',
        'subtotal',
        'service_fee',
        'total_amount',
        'payment_status',
        'used_at'
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'service_fee' => 'integer',
        'total_amount' => 'integer',
        'used_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'user_id'
        );
    }

    public function museum()
    {
        return $this->hasOneThrough(
            Museum::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'museum_id'
        );
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
