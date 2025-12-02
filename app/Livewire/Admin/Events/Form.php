<?php

namespace App\Livewire\Admin\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category; // Assuming Category model exists
use App\Services\Admin\EventService;
use Illuminate\Support\Str; // For slug generation
use App\Models\Speaker; // Import Speaker model

class Form extends Component
{
    use WithFileUploads;

    public EventForm $form; // Inject the form object

    // List of categories for the dropdown
    public $categories;

    // Optional: Event ID for editing. Null for creation.
    public ?int $eventId = null;

    // Multi-step form properties
    public int $currentStep = 1;
    public int $totalSteps = 4; // Now: 1. Info, 2. Tiket, 3. Pembicara, 4. Galeri

    // List of available speakers for the multiselect
    public $allSpeakers;

    public function goToStep(int $step)
    {
        $this->currentStep = $step;
    }

    public function nextStep()
    {
        // Validate current step before proceeding
        if ($this->currentStep === 1) {
            $this->validate([
                'form.title' => 'required|string|min:3|max:255',
                'form.description' => 'required|string',
                'form.jadwal' => 'required|date',
                'form.banner_image' => 'nullable|image|max:2048',
                'form.status' => 'required|in:draft,published',
                'form.category_id' => 'nullable|exists:categories,id',
            ]);
        } elseif ($this->currentStep === 2) {
             $this->validate([
                'form.tickets' => 'array|min:1',
                'form.tickets.*.name' => 'required|string|max:255',
                'form.tickets.*.harga' => 'required|numeric|min:0',
                'form.tickets.*.stok' => 'required|integer|min:1',
             ]);
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'form.selectedSpeakers' => 'nullable|array',
                'form.selectedSpeakers.*' => 'integer|exists:speakers,id',
            ]);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function mount(?int $eventId = null)
    {
        $this->eventId = $eventId;
        $this->categories = Category::all(['id', 'name']);
        $this->allSpeakers = Speaker::all(['id', 'name', 'title']);

        if ($this->eventId) {
            $event = \App\Models\Event::findOrFail($this->eventId);
            $this->form->setEvent($event);
        }
    }

    /**
     * Livewire hook: Called when form.title property is updated.
     * Generates a slug automatically.
     */
    public function updatedFormTitle($value)
    {
        // Only auto-generate slug if we are creating a new event
        if (!$this->eventId) {
            $this->form->slug = Str::slug($value);
        }
    }

    /**
     * Adds a new empty ticket row to the form.
     */
    public function addTicket()
    {
        $this->form->addTicket();
    }

    /**
     * Removes a ticket row from the form by its index.
     */
    public function removeTicket(int $index)
    {
        $this->form->removeTicket($index);
    }

    /**
     * Removes an image from the existing gallery array.
     */
    public function removeExistingImage(string $path)
    {
        $this->form->existingGallery = array_diff($this->form->existingGallery, [$path]);
    }

    /**
     * Handles the form submission for creating/updating an event.
     */
    public function save(EventService $eventService)
    {
        // Final validation for all properties before saving
        $this->form->validate();

        try {
            // Exclude arrays from the main event data array
            $eventData = $this->form->except('tickets', 'selectedSpeakers', 'existingGallery', 'newGallery');

            // Handle banner image upload
            if ($this->form->banner_image && !is_string($this->form->banner_image)) {
                $eventData['banner_url'] = $this->form->banner_image->store('events/banners', 'public');
            }

            if ($this->eventId) {
                // Update existing event
                $event = $eventService->updateEvent($this->eventId, $eventData);
                $message = 'Event berhasil diperbarui!';
            } else {
                // Create new event
                $eventData['organizer_id'] = auth()->id();
                $event = $eventService->createEvent($eventData);
                $message = 'Event berhasil dibuat!';
            }

            // Handle Gallery Uploads
            $newImagePaths = [];
            foreach ($this->form->newGallery as $image) {
                if (is_object($image) && method_exists($image, 'store')) {
                    $newImagePaths[] = $image->store('events/' . $event->id . '/gallery', 'public');
                }
            }

            // Merge existing (and not removed) images with new ones
            $finalGalleryPaths = array_merge($this->form->existingGallery, $newImagePaths);

            // Sync the related data
            $eventService->syncTickets($event, $this->form->tickets);
            $eventService->syncSpeakers($event, $this->form->selectedSpeakers);
            $eventService->syncGallery($event, $finalGalleryPaths);


            $this->dispatch('close-modal');
            $this->dispatch('event-saved', message: $message);
            $this->form->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('error-toast', message: 'Gagal menyimpan event: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.events.form');
    }
}
