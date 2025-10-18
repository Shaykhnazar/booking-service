<?php

namespace App\ValueObjects;

use Carbon\Carbon;

/**
 * Value Object representing a time slot
 * Immutable by design (SOLID: Single Responsibility)
 */
final readonly class TimeSlot
{
    public function __construct(
        public Carbon $startTime,
        public Carbon $endTime
    ) {
        if ($this->startTime->gte($this->endTime)) {
            throw new \InvalidArgumentException('Start time must be before end time');
        }
    }

    public function overlaps(self $other): bool
    {
        return $this->startTime->lt($other->endTime) && $this->endTime->gt($other->startTime);
    }

    public function toArray(): array
    {
        return [
            'start_time' => $this->startTime->toIso8601String(),
            'end_time' => $this->endTime->toIso8601String(),
        ];
    }
}
