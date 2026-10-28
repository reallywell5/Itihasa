<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_user_id',
        'title',
        'message',
        'urgency',
        'target_role',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', Carbon::now());
            });
    }

    public function scopeForRole($query, ?string $role = null)
    {
        return $query->where(function ($q) use ($role) {
            $q->where('target_role', 'all');
            if ($role) {
                $q->orWhere('target_role', $role);
            }
        });
    }
}
