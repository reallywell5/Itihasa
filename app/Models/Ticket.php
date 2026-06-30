<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'museum_id',
        'ticket_name',
        'price',
        'slot',
    ];

    // Relasi dengan Museum
    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    // Relasi dengan Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Scope untuk ticket aktif (slot > 0)
    public function scopeAvailable($query)
    {
        return $query->where('slot', '>', 0);
    }
}
