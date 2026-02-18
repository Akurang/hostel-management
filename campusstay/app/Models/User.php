<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'phone',
        'university',
        'academic_year',
        'is_active',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function hostels(): HasMany
    {
        return $this->hasMany(Hostel::class, 'manager_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    public function waitlists(): HasMany
    {
        return $this->hasMany(Waitlist::class, 'student_id');
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
