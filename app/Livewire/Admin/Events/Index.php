<?php

namespace App\Livewire\Admin\Events;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Services\Admin\EventService;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    // Filter properties
    public string $search = '';
    public string $filterStatus = 'published';

    // Properties for modal confirmation
    public ?int $selectedEventId = null;
    public string $modalTitle = '';
    public string $modalMessage = '';
        public string $modalActionName = '';

        // Properties for the create/edit form modal
        public string $formModalTitle = 'Tambah Event Baru';
        public ?int $editingEventId = null;

        /**
         * Reset pagination when search or filter changes.
         */
        public function updated($propertyName)
        {
            if (in_array($propertyName, ['search', 'filterStatus'])) {
                $this->resetPage();
            }
        }

        /**
         * Listen for the 'event-saved' event and refresh the component.
         */
        #[On('event-saved')]
        public function refreshEventList()
        {
            $this->resetPage();
        }

        /**
         * Prepare the modal for creating a new event.
         */
        public function prepareCreate()
        {
            $this->editingEventId = null;
            $this->formModalTitle = 'Tambah Event Baru';
            $this->dispatch('open-modal', name: 'event-form-modal');
        }

        /**
         * Prepare the modal for editing an existing event.
         */
        public function prepareEdit(int $eventId)
        {
            $this->editingEventId = $eventId;
            $this->formModalTitle = 'Edit Event';
            $this->dispatch('open-modal', name: 'event-form-modal');
        }

        /**
         * Prepare delete confirmation modal.
         */
    public function confirmDelete(int $id)
    {
        $this->selectedEventId = $id;
        $this->modalTitle = 'Konfirmasi Hapus Event';
        $this->modalMessage = 'Anda yakin ingin menghapus event ini? Event akan dipindahkan ke Trash.';
        $this->modalActionName = 'delete';
        $this->dispatch('open-modal', name: 'confirmation-modal');
    }

    /**
     * Perform the delete action.
     */
    public function delete(EventService $eventService)
    {
        if ($this->selectedEventId) {
            $eventService->deleteEvent($this->selectedEventId);
            $this->dispatch('close-modal');
            // Optionally, add a success toast notification here
        }
    }

    /**
     * Prepare restore confirmation modal.
     */
    public function confirmRestore(int $id)
    {
        $this->selectedEventId = $id;
        $this->modalTitle = 'Konfirmasi Restore Event';
        $this->modalMessage = 'Anda yakin ingin mengembalikan event ini dari Trash?';
        $this->modalActionName = 'restore';
        $this->dispatch('open-modal', name: 'confirmation-modal');
    }

    /**
     * Perform the restore action.
     */
    public function restore(EventService $eventService)
    {
        if ($this->selectedEventId) {
            $eventService->restoreEvent($this->selectedEventId);
            $this->dispatch('close-modal');
        }
    }

     /**
     * Prepare force delete confirmation modal.
     */
    public function confirmForceDelete(int $id)
    {
        $this->selectedEventId = $id;
        $this->modalTitle = 'Konfirmasi Hapus Permanen';
        $this->modalMessage = 'ANDA YAKIN? Event ini akan dihapus secara permanen dan tidak bisa dikembalikan.';
        $this->modalActionName = 'forceDelete';
        $this->dispatch('open-modal', name: 'confirmation-modal');
    }

    /**
     * Perform the force delete action.
     */
    public function forceDelete(EventService $eventService)
    {
        if ($this->selectedEventId) {
            $eventService->forceDeleteEvent($this->selectedEventId);
            $this->dispatch('close-modal');
        }
    }

    /**
     * Handle the modal action dynamically.
     */
    public function handleModalAction(EventService $eventService)
    {
        if ($this->modalActionName) {
            $this->{$this->modalActionName}($eventService);
        }
    }
    #[Title('Manajemen Event')]
    public function render(EventService $eventService)
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->filterStatus,
        ];

        $events = $eventService->getEvents($filters);

        return view('livewire.admin.events.index', [
            'events' => $events,
        ]);
    }
}
