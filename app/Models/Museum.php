<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Museum extends Model
{
    use HasFactory;

    protected $table = 'museums';

    protected $fillable = [
        'name',
        'address',
        'description',
        'image',
        'rating',
        'opening_time',
        'closing_time',
    ];

    // Relasi
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
