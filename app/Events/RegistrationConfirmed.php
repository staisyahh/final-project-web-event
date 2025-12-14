<?php

namespace App\Events;

use App\Models\Registration; // Tambahkan ini
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Registration $registration; // Tambahkan properti ini

    /**
     * Create a new event instance.
     */
    public function __construct(Registration $registration) // Modifikasi konstruktor
    {
        $this->registration = $registration;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
