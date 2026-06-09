<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'transaction_id',
        'qr_code',
        'scan_status',
        'scanned_at',
    ];

    protected $casts = [
        'scan_status' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
