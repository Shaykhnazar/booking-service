<?php

namespace App\Repositories;

use App\Contracts\BookingRepositoryInterface;
use App\Models\Booking;
use App\ValueObjects\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Repository implementation (SOLID: Single Responsibility)
 */
class BookingRepository implements BookingRepositoryInterface
{
    public function findOverlappingBookings(int $serviceId, TimeSlot $timeSlot): Collection
    {
        return Booking::where('service_id', $serviceId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($timeSlot) {
                $query->whereBetween('start_time', [
                    $timeSlot->startTime,
                    $timeSlot->endTime
                ])
                ->orWhereBetween('end_time', [
                    $timeSlot->startTime,
                    $timeSlot->endTime
                ])
                ->orWhere(function ($q) use ($timeSlot) {
                    $q->where('start_time', '<=', $timeSlot->startTime)
                      ->where('end_time', '>=', $timeSlot->endTime);
                });
            })
            ->get();
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function findByDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        return Booking::with('service')
            ->whereBetween('start_time', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();
    }
}
