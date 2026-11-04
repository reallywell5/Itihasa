<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrScanLog extends Model
{
    use MassPrunable;

    protected $fillable = [
        'transaction_id',
        'scanned_by',
        'qr_code_input',
        'status',
        'message',
        'gate_incident_tag',
        'gate_notes',
        'scanned_pax',
        'is_archived',
        'scanned_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
            'scanned_pax' => 'integer',
            'is_archived' => 'boolean',
        ];
    }

    /**
     * Tentukan kriteria data log yang dipangkas otomatis (auto-prune):
     * 1. Log gagal ('failed') yang berumur lebih dari 30 hari.
     * 2. Log yang sudah tersinkron ke ringkasan harian/berkas arsip dan berusia lebih dari 6 bulan.
     */
    public function prunable(): Builder
    {
        return static::where(function (Builder $query) {
            $query->where('status', 'failed')
                ->where('created_at', '<=', now()->subDays(30));
        })->orWhere(function (Builder $query) {
            $query->where('is_archived', true)
                ->where('created_at', '<=', now()->subMonths(6));
        });
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
