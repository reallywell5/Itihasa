<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'museum_id',
        'visit_date',
        'adult_qty',
        'student_qty',
        'child_qty',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }
}
