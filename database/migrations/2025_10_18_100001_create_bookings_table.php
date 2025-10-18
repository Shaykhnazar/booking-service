<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_phone');
            $table->dateTime('start_time'); // Booking start time in UTC
            $table->dateTime('end_time'); // Booking end time in UTC (start_time + service_duration + 30 min)
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();

            // Indexes for performance
            $table->index(['service_id', 'start_time', 'end_time']);
            $table->index(['start_time', 'end_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
