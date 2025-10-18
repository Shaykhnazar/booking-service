<?php

namespace App\Services;

use App\Contracts\BookingRepositoryInterface;
use App\Models\Service;
use App\ValueObjects\BookingPeriod;
use App\ValueObjects\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Service for checking booking availability (SOLID: Single Responsibility)
 */
class BookingAvailabilityService
{
    private const SLOT_INTERVAL_MINUTES = 30;

    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository
    ) {
    }

    /**
     * Generate available time slots for a service on a specific date
     */
    public function getAvailableSlots(Service $service, Carbon $date): Collection
    {
        $period = new BookingPeriod($date);

        // Check if it's a working day
        if (!$period->isWorkingDay()) {
            return collect();
        }

        $allSlots = $this->generatePossibleSlots($period, $service);
        $bookedSlots = $this->getBookedSlots($service->id, $date);

        return $allSlots->filter(function (TimeSlot $slot) use ($bookedSlots) {
            return !$this->isSlotBooked($slot, $bookedSlots);
        });
    }

    /**
     * Generate all possible time slots for a day
     */
    private function generatePossibleSlots(BookingPeriod $period, Service $service): Collection
    {
        $slots = collect();
        $currentTime = $period->getStartOfWorkDay();
        $endOfDay = $period->getEndOfWorkDay();
        $totalDuration = $service->getTotalDurationMinutes();

        while ($currentTime->clone()->addMinutes($totalDuration)->lte($endOfDay)) {
            $slotEnd = $currentTime->clone()->addMinutes($totalDuration);

            $slots->push(new TimeSlot($currentTime->clone(), $slotEnd));

            $currentTime->addMinutes(self::SLOT_INTERVAL_MINUTES);
        }

        return $slots;
    }

    /**
     * Get all booked time slots for a service on a specific date
     */
    private function getBookedSlots(int $serviceId, Carbon $date): Collection
    {
        $period = new BookingPeriod($date);
        $startOfDay = $period->getStartOfWorkDay()->setTimezone('UTC');
        $endOfDay = $period->getEndOfWorkDay()->setTimezone('UTC');

        $bookings = $this->bookingRepository->findOverlappingBookings(
            $serviceId,
            new TimeSlot($startOfDay, $endOfDay)
        );

        return $bookings->map(fn ($booking) => new TimeSlot(
            $booking->start_time->clone(),
            $booking->end_time->clone()
        ));
    }

    /**
     * Check if a slot overlaps with any booked slots
     */
    private function isSlotBooked(TimeSlot $slot, Collection $bookedSlots): bool
    {
        return $bookedSlots->contains(fn (TimeSlot $booked) => $slot->overlaps($booked));
    }

    /**
     * Check if a specific time slot is available
     */
    public function isSlotAvailable(Service $service, TimeSlot $timeSlot): bool
    {
        $period = new BookingPeriod($timeSlot->startTime);

        // Validate working day
        if (!$period->isWorkingDay()) {
            return false;
        }

        // Validate working hours
        if (!$period->isWithinWorkingHours($timeSlot->startTime) ||
            !$period->isWithinWorkingHours($timeSlot->endTime->subSecond())) {
            return false;
        }

        // Check for overlapping bookings
        $overlapping = $this->bookingRepository->findOverlappingBookings(
            $service->id,
            $timeSlot
        );

        return $overlapping->isEmpty();
    }
}
