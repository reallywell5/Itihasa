<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'museum_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'staff';
    }

    public function isVisitor()
    {
        return $this->role === 'visitor';
    }

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
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
