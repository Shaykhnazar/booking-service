<?php

namespace App\Http\Controllers;

use App\DTOs\CreateBookingDTO;
use App\Exceptions\BookingNotAvailableException;
use App\Http\Requests\CreateBookingRequest;
use App\Models\Service;
use App\Services\BookingAvailabilityService;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly BookingAvailabilityService $availabilityService
    ) {
    }

    /**
     * Get available slots for a service on a specific date
     */
    public function availableSlots(Request $request, Service $service): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date)->startOfDay();
        $slots = $this->availabilityService->getAvailableSlots($service, $date);

        return response()->json([
            'slots' => $slots->map(fn ($slot) => [
                'start_time' => $slot->startTime->setTimezone('Europe/Moscow')->format('H:i'),
                'start_time_iso' => $slot->startTime->toIso8601String(),
                'end_time' => $slot->endTime->setTimezone('Europe/Moscow')->format('H:i'),
            ])->values(),
        ]);
    }

    /**
     * Create a new booking
     */
    public function store(CreateBookingRequest $request): JsonResponse
    {
        try {
            $dto = CreateBookingDTO::fromRequest($request->validated());
            $booking = $this->bookingService->createBooking($dto);

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking->load('service'),
            ], 201);
        } catch (BookingNotAvailableException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
