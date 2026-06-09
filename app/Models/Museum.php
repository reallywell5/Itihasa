<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Museum extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'image',
        'opening_time',
        'closing_time',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
