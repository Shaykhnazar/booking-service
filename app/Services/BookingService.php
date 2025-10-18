<?php

namespace App\Services;

use App\Contracts\BookingRepositoryInterface;
use App\DTOs\CreateBookingDTO;
use App\Exceptions\BookingNotAvailableException;
use App\Models\Booking;
use App\Models\Service;
use App\ValueObjects\TimeSlot;
use Illuminate\Support\Facades\DB;

/**
 * Main booking service (SOLID: Single Responsibility)
 */
class BookingService
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingAvailabilityService $availabilityService
    ) {
    }

    /**
     * Create a new booking with race condition handling
     *
     * @throws BookingNotAvailableException
     */
    public function createBooking(CreateBookingDTO $dto): Booking
    {
        return DB::transaction(function () use ($dto) {
            // Lock the service row to prevent race conditions
            $service = Service::lockForUpdate()->findOrFail($dto->serviceId);

            // Calculate booking time slot
            $endTime = $dto->startTime->clone()->addMinutes($service->getTotalDurationMinutes());
            $timeSlot = new TimeSlot($dto->startTime->clone()->setTimezone('UTC'), $endTime->setTimezone('UTC'));

            // Validate availability within the transaction
            if (!$this->availabilityService->isSlotAvailable($service, $timeSlot)) {
                throw new BookingNotAvailableException('The selected time slot is not available');
            }

            // Create booking
            return $this->bookingRepository->create([
                'service_id' => $dto->serviceId,
                'client_name' => $dto->clientName,
                'client_phone' => $dto->clientPhone,
                'start_time' => $timeSlot->startTime,
                'end_time' => $timeSlot->endTime,
                'status' => 'confirmed',
            ]);
        });
    }
}
