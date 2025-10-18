<?php

namespace App\Contracts;

use App\Models\Booking;
use App\ValueObjects\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Repository interface (SOLID: Dependency Inversion Principle)
 */
interface BookingRepositoryInterface
{
    public function findOverlappingBookings(int $serviceId, TimeSlot $timeSlot): Collection;

    public function create(array $data): Booking;

    public function findByDateRange(Carbon $startDate, Carbon $endDate): Collection;
}
