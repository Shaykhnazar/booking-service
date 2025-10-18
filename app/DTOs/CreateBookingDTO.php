<?php

namespace App\DTOs;

use Carbon\Carbon;

/**
 * DTO for booking creation (SOLID: Single Responsibility)
 */
final readonly class CreateBookingDTO
{
    public function __construct(
        public int $serviceId,
        public string $clientName,
        public string $clientPhone,
        public Carbon $startTime
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            serviceId: $data['service_id'],
            clientName: $data['client_name'],
            clientPhone: $data['client_phone'],
            startTime: Carbon::parse($data['start_time'])
        );
    }

    public function toArray(): array
    {
        return [
            'service_id' => $this->serviceId,
            'client_name' => $this->clientName,
            'client_phone' => $this->clientPhone,
            'start_time' => $this->startTime->toIso8601String(),
        ];
    }
}
