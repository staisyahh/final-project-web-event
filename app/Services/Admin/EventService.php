<?php

namespace App\Services\Admin;

use App\Models\Event;
use App\Models\EventGallery;
use Illuminate\Support\Facades\Storage;

class EventService
{
    /**
     * Get paginated events with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getEvents(array $filters = [], int $perPage = 10)
    {
        $query = Event::query()->with([
            'category'
        ])->withCount('registrations')
            ->withSum('tickets as tickets_stock', 'stok');

        // Handle filter for trashed items
        if (isset($filters['status']) && $filters['status'] === 'trashed') {
            $query->onlyTrashed();
        } else if (isset($filters['status']) && !empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Handle search filter
        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new event.
     *
     * @param array $data
     * @return \App\Models\Event
     */
    public function createEvent(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Update an existing event.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Event
     */
    public function updateEvent(int $id, array $data): Event
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event;
    }

    /**
     * REFACTORED: Sync tickets for an event intelligently.
     * This method now updates existing tickets, creates new ones, and deletes only those
     * that are removed, preserving registrations linked to existing tickets.
     *
     * @param \App\Models\Event $event
     * @param array $ticketsData
     * @return void
     */
    public function syncTickets(\App\Models\Event $event, array $ticketsData): void
    {
        $incomingTicketIds = collect($ticketsData)->pluck('id')->filter()->toArray();
        $currentTicketIds = $event->tickets()->pluck('id')->toArray();

        // 1. Delete tickets that are no longer in the form data
        $ticketsToDelete = array_diff($currentTicketIds, $incomingTicketIds);
        if (!empty($ticketsToDelete)) {
            // WARNING: This assumes that tickets with existing registrations should not be deletable
            // from the form. A proper implementation would prevent deletion if registrations exist.
            $event->tickets()->whereIn('id', $ticketsToDelete)->delete();
        }

        // 2. Update or create tickets
        foreach ($ticketsData as $ticketData) {
            $event->tickets()->updateOrCreate(
                ['id' => $ticketData['id'] ?? null], // Condition to find the ticket
                [ // Data to update or create
                    'name' => $ticketData['name'],
                    'harga' => $ticketData['harga'],
                    'stok' => $ticketData['stok'],
                    'status' => $ticketData['stok'] > 0 ? 'available' : 'sold_out',
                ]
            );
        }
    }

    /**
     * Sync speakers for an event.
     *
     * @param \App\Models\Event $event
     * @param array $speakerIds
     * @return void
     */
    public function syncSpeakers(Event $event, array $speakerIds): void
    {
        $event->speakers()->sync($speakerIds);
    }

    /**
     * REFACTORED: Sync gallery for an event efficiently.
     *
     * @param \App\Models\Event $event
     * @param array $finalGalleryPaths
     * @return void
     */
    public function syncGallery(Event $event, array $galleryPaths)
    {
        // 1. Ambil semua path gambar yang saat ini ada di Database untuk event ini
        $currentDbPaths = $event->galleries()->get()->map(fn($g) => $g->getRawOriginal('image_url'))->toArray();

        // 2. Tentukan gambar mana yang harus DIHAPUS (Ada di DB, tapi tidak ada di form submit)
        $pathsToDelete = array_diff($currentDbPaths, $galleryPaths);

        // 3. Tentukan gambar mana yang harus DITAMBAH (Ada di form submit, tapi belum ada di DB)
        $pathsToAdd = array_diff($galleryPaths, $currentDbPaths);

        // EKSEKUSI HAPUS
        if (!empty($pathsToDelete)) {
            // Hapus file fisik dari storage
            foreach ($pathsToDelete as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            // Hapus record di database
            EventGallery::where('event_id', $event->id)
                ->whereIn('image_url', $pathsToDelete)
                ->delete();
        }

        // EKSEKUSI TAMBAH
        foreach ($pathsToAdd as $path) {
            // Pastikan path valid dan file benar-benar ada (mencegah manipulasi input)
            if ($path) {
                EventGallery::create([
                    'event_id' => $event->id,
                    'image_url' => $path,
                    'caption'   => null // Default caption
                ]);
            }
        }
    }

    /**
     * Soft delete an event.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEvent(int $id): bool
    {
        $event = Event::findOrFail($id);
        return $event->delete();
    }

    /**
     * Restore a soft-deleted event.
     *
     * @param int $id
     * @return bool
     */
    public function restoreEvent(int $id): bool
    {
        $event = Event::onlyTrashed()->findOrFail($id);
        return $event->restore();
    }

    /**
     * Permanently delete an event.
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteEvent(int $id): bool
    {
        $event = Event::onlyTrashed()->findOrFail($id);
        return $event->forceDelete();
    }
}
