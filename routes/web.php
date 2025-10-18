<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServiceController::class, 'index'])->name('home');

Route::prefix('api')->group(function () {
    Route::get('services/{service}/available-slots', [BookingController::class, 'availableSlots'])
        ->name('bookings.available-slots');

    Route::post('bookings', [BookingController::class, 'store'])
        ->name('bookings.store');
});
