<?php

namespace App\Queries\Admin;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class AbsensiQuery
{
    /**
     * Get events that are relevant for check-in.
     * (e.g., published and not too far in the past).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableEvents(): Collection
    {
        // For simplicity, we get all published events.
        // This could be refined to only show events happening 'today' or 'in the future'.
        return Event::where('status', 'published')
            ->orderBy('jadwal', 'desc')
            ->get();
    }
}
