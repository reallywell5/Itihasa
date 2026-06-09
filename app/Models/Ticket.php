<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'museum_id',
        'ticket_name',
        'price',
        'slot',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
