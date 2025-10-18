<?php

namespace App\ValueObjects;

use Carbon\Carbon;

/**
 * Value Object representing the allowed booking period
 */
final readonly class BookingPeriod
{
    private const WORK_START_HOUR = 10;
    private const WORK_END_HOUR = 20;
    private const TIMEZONE = 'Europe/Moscow';

    public function __construct(
        public Carbon $date
    ) {
    }

    public function getStartOfWorkDay(): Carbon
    {
        return $this->date->clone()
            ->timezone(self::TIMEZONE)
            ->setTime(self::WORK_START_HOUR, 0, 0);
    }

    public function getEndOfWorkDay(): Carbon
    {
        return $this->date->clone()
            ->timezone(self::TIMEZONE)
            ->setTime(self::WORK_END_HOUR, 0, 0);
    }

    public function isWorkingDay(): bool
    {
        return !$this->date->isSunday();
    }

    public function isWithinWorkingHours(Carbon $time): bool
    {
        $moscowTime = $time->clone()->timezone(self::TIMEZONE);

        return $moscowTime->hour >= self::WORK_START_HOUR
            && $moscowTime->hour < self::WORK_END_HOUR;
    }
}
