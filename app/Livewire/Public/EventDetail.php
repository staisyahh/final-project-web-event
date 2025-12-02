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
            'tickets' => fn($query) => $query->where('stok', '>', 0)->where('status', 'available'), // Only load available tickets
            'galleries'
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
