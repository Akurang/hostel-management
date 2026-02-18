<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'reference',
        'student_id',
        'hostel_id',
        'room_id',
        'status',
        'academic_year',
        'semester',
        'rejection_reason',
        'payment_deadline',
        'approved_at',
        'confirmed_at',
    ];

    protected $casts = [
        'payment_deadline' => 'datetime',
        'approved_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Booking $booking): void {
            $count = static::withTrashed()->count();
            $booking->reference = 'CS-' . date('Y') . '-' . str_pad((string) ($count + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
