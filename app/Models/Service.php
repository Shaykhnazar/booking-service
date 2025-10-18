<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get total booking duration including buffer time (30 minutes)
     */
    public function getTotalDurationMinutes(): int
    {
        return $this->duration_minutes + 30;
    }
}
