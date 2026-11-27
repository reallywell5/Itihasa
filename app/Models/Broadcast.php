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
        'category',
        'target_role',
        'display_type',
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

    public function reads()
    {
        return $this->hasMany(BroadcastRead::class);
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
        if ($role === 'super_admin') {
            return $query->where(function ($q) {
                $q->where('target_role', 'all')
                    ->orWhere('target_role', 'super_admin');
            });
        }

        return $query->where(function ($q) use ($role) {
            $q->where('target_role', 'all');
            if ($role) {
                $q->orWhere('target_role', $role);
                if ($role === 'visitor') {
                    $q->orWhere('target_role', 'user');
                }
                if ($role === 'user') {
                    $q->orWhere('target_role', 'visitor');
                }
            }
        });
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    public function markAsReadBy(User $user): void
    {
        BroadcastRead::firstOrCreate([
            'broadcast_id' => $this->id,
            'user_id' => $user->id,
        ], [
            'read_at' => now(),
        ]);
    }

    public function getReadCountAttribute(): int
    {
        return $this->reads()->count();
    }
}
