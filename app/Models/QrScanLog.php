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
        'gate_incident_tag',
        'gate_notes',
        'scanned_pax',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'scanned_pax' => 'integer',
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
