<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promos';

    protected $fillable = [
        'museum_id',
        'code',
        'title',
        'discount_type',
        'discount_value',
        'min_total_price',
        'max_discount',
        'quota',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'discount_value' => 'integer',
            'min_total_price' => 'integer',
            'max_discount' => 'integer',
            'quota' => 'integer',
            'used_count' => 'integer',
        ];
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if promo is currently valid.
     */
    public function isValid(?string $date = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->quota > 0 && $this->used_count >= $this->quota) {
            return false;
        }

        $checkDate = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $start = Carbon::parse($this->start_date)->startOfDay();
        $end = Carbon::parse($this->end_date)->endOfDay();

        return $checkDate->betweenIncluded($start, $end);
    }

    /**
     * Calculate discount amount for a given total price.
     */
    public function calculateDiscount(int $totalPrice): int
    {
        if ($totalPrice < $this->min_total_price) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            $discount = (int) round($totalPrice * ($this->discount_value / 100));
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }

            return min($discount, $totalPrice);
        }

        return min($this->discount_value, $totalPrice);
    }
}
