<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    protected $fillable = [
        'user_id',
        'museum_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class, 'museum_id');
    }
}
