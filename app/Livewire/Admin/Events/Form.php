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
                'form.status' => 'required|in:draft,published,completed,archived',
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
        // Auto-generate slug for new events or allow manual change for existing ones
        if (!$this->eventId) {
            $this->form->slug = Str::slug($value);
        }
    }

    /**
     * Regenerates the slug from the title.
     */
    public function regenerateSlug()
    {
        $this->form->slug = Str::slug($this->form->title);
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
        $this->form->validate();

        try {
            // 1. Ambil data event dasar (kecuali array relation dan file sementara)
            $eventData = $this->form->except('tickets', 'selectedSpeakers', 'existingGallery', 'newGallery');

            // 2. Handle Banner (sama seperti sebelumnya)
            if ($this->form->banner_image && !is_string($this->form->banner_image)) {
                $eventData['banner_url'] = $this->form->banner_image->store('events/banners', 'public');
            }

            // 3. Simpan/Update Data Event Utama DULU untuk mendapatkan ID
            if ($this->eventId) {
                $event = $eventService->updateEvent($this->eventId, $eventData);
            } else {
                $eventData['organizer_id'] = auth()->id();
                $event = $eventService->createEvent($eventData);
                $this->eventId = $event->id;
            }

            // 4. PROSES NEW GALLERY (Perbaikan Utama)
            // Gabungkan gallery lama dengan yang baru di-upload saat tombol save ditekan
            $finalGalleryPaths = $this->form->existingGallery;

            foreach ($this->form->newGallery as $photo) {
                // Pastikan ini adalah file upload Livewire
                if ($photo instanceof \Illuminate\Http\UploadedFile) {
                    // Simpan ke folder public: events/{id}/gallery
                    $path = $photo->store("events/{$event->id}/gallery", 'public');
                    $finalGalleryPaths[] = $path;
                }
            }

            // 5. Sync Data Relasi
            $eventService->syncTickets($event, $this->form->tickets);
            $eventService->syncSpeakers($event, $this->form->selectedSpeakers);

            // Kirim array path lengkap (lama + baru) ke service
            $eventService->syncGallery($event, $finalGalleryPaths);

            // 6. Reset & Feedback
            $this->dispatch('close-modal');
            $this->dispatch('event-saved', message: 'Event berhasil disimpan!');
            $this->form->resetForm();
            $this->eventId = null;
        } catch (\Exception $e) {
            dd($e);
            $this->dispatch('error-toast', message: 'Gagal menyimpan event: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.events.form');
    }
}
