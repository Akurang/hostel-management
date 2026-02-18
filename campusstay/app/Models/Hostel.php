<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hostel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'manager_id',
        'name',
        'slug',
        'description',
        'gender_policy',
        'address',
        'distance_from_campus',
        'university',
        'latitude',
        'longitude',
        'is_verified',
        'is_active',
        'images',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    protected $appends = ['average_rating', 'total_reviews', 'available_room_types_count'];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_visible', true);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'hostel_amenities');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews->avg('rating') ?? 0, 1);
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews->count();
    }

    public function getAvailableRoomTypesCountAttribute(): int
    {
        return $this->rooms->where('available_beds', '>', 0)->count();
    }
}
