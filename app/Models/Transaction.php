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
        'total_amount',
        'payment_status',
        'expired_at',
        'used_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'total_amount' => 'integer',
        'expired_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
