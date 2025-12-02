<?php

namespace App\Providers;

use App\Events\RegistrationConfirmed; // Tambahkan ini
use App\Listeners\GenerateETicketsOnRegistrationConfirmed; // Tambahkan ini
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        RegistrationConfirmed::class => [
            GenerateETicketsOnRegistrationConfirmed::class,
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
