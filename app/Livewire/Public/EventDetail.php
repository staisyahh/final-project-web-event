<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Endroid\QrCode\Builder\Builder;

#[Layout('components.layouts.front')]
class EventDetail extends Component
{
    public Event $event;

    /**
     * Mount the component with the given event.
     * Route-model binding will automatically resolve the Event model from the slug.
     *
     * @param \App\Models\Event $event
     */
    public function mount(Event $event)
    {
        // Load all necessary relationships for the detail page to avoid N+1 query issues.
        $this->event = $event->load([
            'category',
            'organizer', // Tambahkan eager loading untuk organizer
            'speakers',
            'tickets', // Load all tickets to show sold-out status in UI
            'galleries',
            'reviews.user' // Eager load reviews and the user who made the review
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.public.event-detail')
            ->title('Detail Event: ' . $this->event->title);
    }
}
