<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyScanSummary extends Model
{
    protected $fillable = [
        'date',
        'museum_id',
        'scanned_by',
        'total_scans',
        'successful_scans',
        'failed_scans',
        'total_pax',
        'incidents_count',
        'archive_file',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_scans' => 'integer',
            'successful_scans' => 'integer',
            'failed_scans' => 'integer',
            'total_pax' => 'integer',
            'incidents_count' => 'integer',
        ];
    }

    public function museum(): BelongsTo
    {
        return $this->belongsTo(Museum::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}

