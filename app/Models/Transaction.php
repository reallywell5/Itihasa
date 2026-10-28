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
        'scanned_by_user_id',
        'scanned_manifest',
        'scanned_pax',
        'gate_incident_tag',
        'gate_notes',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'total_amount' => 'integer',
        'expired_at' => 'datetime',
        'used_at' => 'datetime',
        'scanned_manifest' => 'array',
        'scanned_pax' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by_user_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    /**
     * Sisa anggota/pax rombongan yang belum masuk gate.
     */
    public function remainingPax(): int
    {
        $total = (int) ($this->booking->jumlah_anggota ?? 1);

        return max(0, $total - (int) $this->scanned_pax);
    }

    /**
     * Apakah tiket sudah selesai digunakan secara penuh.
     */
    public function isFullyUsed(): bool
    {
        if ($this->used_at !== null) {
            return true;
        }

        $total = (int) ($this->booking->jumlah_anggota ?? 1);

        return $this->scanned_pax >= $total;
    }
}
