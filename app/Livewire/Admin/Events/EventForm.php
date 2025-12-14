<?php

namespace App\Livewire\Admin\Events;

use Livewire\Form;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;

class EventForm extends Form
{
    #[Validate('required|string|min:3|max:255')]
    public string $title = '';

    #[Validate('nullable|string|max:255')]
    public ?string $slug = null; // Akan di-auto-generate atau diisi user

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|date')]
    public string $jadwal = ''; // Format untuk input datetime-local: YYYY-MM-DDTHH:MM

    #[Validate('nullable|string|max:255')]
    public ?string $location_name = null;

    #[Validate('nullable|string')]
    public ?string $location_address = null;

    // Untuk upload file banner. Akan berupa instance UploadedFile.
    #[Validate('nullable|image|max:2048')] // max 2MB
    public $banner_image = null;

    #[Validate(['required', 'in:draft,published,completed,archived,trashed'])]
    public string $status = 'draft';


    #[Validate('nullable|exists:categories,id')]
    public ?int $category_id = null;

    // Tickets repeater
    #[Validate([
        'tickets' => 'array|min:1',
        'tickets.*.id' => 'nullable|integer',
        'tickets.*.name' => 'required|string|max:255',
        'tickets.*.harga' => 'required|numeric|min:0',
        'tickets.*.stok' => 'required|integer|min:1',
    ])]
    public array $tickets = [
        ['id' => null, 'name' => '', 'harga' => 0, 'stok' => 100]
    ];

    // Speakers selection
    #[Validate([
        'selectedSpeakers' => 'nullable|array',
        'selectedSpeakers.*' => 'integer|exists:speakers,id',
    ])]
    public array $selectedSpeakers = [];

    // Gallery images
    public array $existingGallery = [];
    #[Validate([
        'newGallery.*' => 'image|max:2048', // 2MB max per image
    ])]
    public array $newGallery = [];


    /**
     * Mengisi form dengan data event yang sudah ada.
     * Berguna untuk fitur edit event.
     */
    public function setEvent(\App\Models\Event $event)
    {
        $this->title = $event->title;
        $this->slug = $event->slug;
        $this->description = $event->description;
        $this->jadwal = $event->jadwal->format('Y-m-d\TH:i');
        $this->location_name = $event->location_name;
        $this->location_address = $event->location_address;
        $this->status = $event->status;
        $this->category_id = $event->category_id;
        $this->tickets = $event->tickets->map(fn($ticket) => $ticket->only(['id', 'name', 'harga', 'stok']))->toArray();
        $this->selectedSpeakers = $event->speakers->pluck('id')->toArray();

        // Populate existing gallery with raw, relative paths
        $this->existingGallery = $event->galleries
            ->map(fn($gallery) => $gallery->getRawOriginal('image_url'))
            ->toArray();
        $this->newGallery = [];
    }

    /**
     * Mereset semua properti form ke nilai default.
     */
    public function resetForm()
    {
        $this->reset();
        $this->addTicket();
        $this->selectedSpeakers = [];
        $this->existingGallery = [];
        $this->newGallery = [];
    }

    /**
     * Adds a new empty ticket row to the form.
     */
    public function addTicket()
    {
        $this->tickets[] = ['id' => null, 'name' => '', 'harga' => 0, 'stok' => 100];
    }

    /**
     * Removes a ticket row from the form by its index.
     */
    public function removeTicket(int $index)
    {
        unset($this->tickets[$index]);
        $this->tickets = array_values($this->tickets); // Re-index the array
    }
}
