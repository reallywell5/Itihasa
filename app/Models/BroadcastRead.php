<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastRead extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'broadcast_reads';

    protected $fillable = [
        'broadcast_id',
        'user_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

