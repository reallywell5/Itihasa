<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuseumSpecialDate extends Model
{
    use HasFactory;

    protected $table = 'museum_special_dates';

    protected $fillable = [
        'museum_id',
        'date',
        'is_closed',
        'quota_override',
        'notes',
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
            'is_closed' => 'boolean',
            'quota_override' => 'integer',
        ];
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }
}
