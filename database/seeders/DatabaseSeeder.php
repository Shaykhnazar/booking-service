<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create services
        $quadBike30 = Service::create([
            'name' => 'Поездка на квадроцикле (30 минут)',
            'description' => 'Увлекательная получасовая поездка на квадроцикле',
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $quadBike60 = Service::create([
            'name' => 'Поездка на квадроцикле (60 минут)',
            'description' => 'Часовая поездка на квадроцикле с инструктором',
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $enduro60 = Service::create([
            'name' => 'Тур на эндуро (60 минут)',
            'description' => 'Часовой тур на эндуро мотоцикле',
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $enduro120 = Service::create([
            'name' => 'Тур на эндуро (120 минут)',
            'description' => 'Двухчасовой тур на эндуро мотоцикле',
            'duration_minutes' => 120,
            'is_active' => true,
        ]);

        // Moscow timezone
        $timezone = 'Europe/Moscow';

        // Create bookings for October 16, 2024
        $this->createBooking($quadBike30, '2024-10-16 13:00:00', $timezone);
        $this->createBooking($quadBike30, '2024-10-16 16:00:00', $timezone);
        $this->createBooking($quadBike60, '2024-10-16 10:00:00', $timezone);
        $this->createBooking($enduro60, '2024-10-16 10:00:00', $timezone);
        $this->createBooking($enduro60, '2024-10-16 11:30:00', $timezone);
        $this->createBooking($enduro60, '2024-10-16 18:30:00', $timezone);

        // Create bookings for October 17, 2024
        $this->createBooking($quadBike30, '2024-10-17 10:00:00', $timezone);
        $this->createBooking($quadBike30, '2024-10-17 11:00:00', $timezone);
        $this->createBooking($quadBike30, '2024-10-17 13:00:00', $timezone);
        $this->createBooking($quadBike30, '2024-10-17 18:00:00', $timezone);
        $this->createBooking($enduro120, '2024-10-17 14:00:00', $timezone);
    }

    private function createBooking(Service $service, string $startTime, string $timezone): void
    {
        $start = Carbon::parse($startTime, $timezone)->setTimezone('UTC');
        $end = $start->clone()->addMinutes($service->getTotalDurationMinutes());

        Booking::create([
            'service_id' => $service->id,
            'client_name' => 'Test User',
            'client_phone' => '+79991234567',
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'confirmed',
        ]);
    }
}
