<?php

namespace App\Listeners;

use App\Events\RegistrationConfirmed;
use App\Models\ETicket; // Tambahkan ini
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str; // Tambahkan ini

class GenerateETicketsOnRegistrationConfirmed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RegistrationConfirmed $event): void
    {
        $registration = $event->registration;

        // Loop for the number of tickets purchased in this registration
        for ($i = 0; $i < $registration->jumlah_tiket; $i++) {
            ETicket::create([
                'registration_id' => $registration->id,
                'event_id' => $registration->event_id,
                'ticket_code' => Str::upper(Str::random(10)), // Generate a unique code
                'status' => 'active',
            ]);
        }
    }
}
