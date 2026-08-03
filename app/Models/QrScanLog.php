<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrScanLog extends Model
{
    protected $fillable = [
        'transaction_id',
        'scanned_by',
        'qr_code_input',
        'status',
        'message',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
