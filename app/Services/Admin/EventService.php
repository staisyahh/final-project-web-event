<?php

namespace App\Services\Admin;

use App\Models\Event;

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
        $query = Event::query()->with('category')->withCount('registrations');

        // Handle filter for trashed items
        if (isset($filters['status']) && $filters['status'] === 'trashed') {
            $query->onlyTrashed();
        } else if (isset($filters['status']) && $filters['status'] !== 'all') {
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
     * Sync tickets for an event. Deletes old tickets and creates new ones.
     *
     * @param \App\Models\Event $event
     * @param array $ticketsData
     * @return void
     */
    public function syncTickets(\App\Models\Event $event, array $ticketsData): void
    {
        $event->tickets()->delete();
        $event->tickets()->createMany($ticketsData);
    }

    /**
     * Sync speakers for an event.
     *
     * @param \App\Models\Event $event
     * @param array $speakerIds
     * @return void
     */
    public function syncSpeakers(\App\Models\Event $event, array $speakerIds): void
    {
        $event->speakers()->sync($speakerIds);
    }

    /**
     * Sync gallery for an event.
     *
     * @param \App\Models\Event $event
     * @param array $galleryPaths
     * @return void
     */
    public function syncGallery(\App\Models\Event $event, array $finalGalleryPaths): void
    {
        // Get the current gallery image paths from the database
        $currentImagePaths = $event->galleries()->pluck('image_url')->toArray();

        // Determine which images to delete from storage
        $imagesToDelete = array_diff($currentImagePaths, $finalGalleryPaths);

        // Delete the physical files from storage
        if (!empty($imagesToDelete)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($imagesToDelete);
        }

        // Now, sync the database
        // First, delete all old gallery entries for this event
        $event->galleries()->delete();

        // Then, create new gallery entries from the provided final paths
        if (!empty($finalGalleryPaths)) {
            $imageData = array_map(fn($path) => ['image_url' => $path], $finalGalleryPaths);
            $event->galleries()->createMany($imageData);
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
