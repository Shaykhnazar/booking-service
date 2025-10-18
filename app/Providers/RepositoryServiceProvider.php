<?php

namespace App\Providers;

use App\Contracts\BookingRepositoryInterface;
use App\Repositories\BookingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
