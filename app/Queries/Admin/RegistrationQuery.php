<?php

namespace App\Queries\Admin;

use App\Models\Registration;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RegistrationQuery
{
    /**
     * Get paginated registrations with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Registration::query()->with(['user', 'event', 'ticket']);

        // Filter by event
        $query->when($filters['event_id'] ?? null, function ($q, $eventId) {
            return $q->where('event_id', $eventId);
        });

        // Filter by status
        $query->when($filters['status'] ?? null, function ($q, $status) {
            return $q->where('status', $status);
        });

        // Filter by date range
        $query->when($filters['start_date'] ?? null, function ($q, $startDate) {
            return $q->whereDate('created_at', '>=', $startDate);
        });
        $query->when($filters['end_date'] ?? null, function ($q, $endDate) {
            return $q->whereDate('created_at', '<=', $endDate);
        });

        // Handle search filter
        $query->when($filters['search'] ?? null, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        });

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get all filtered registrations.
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFiltered(array $filters = [])
    {
        $query = Registration::query()->with(['user', 'event', 'ticket']);

        // Filter by event
        $query->when($filters['event_id'] ?? null, function ($q, $eventId) {
            return $q->where('event_id', $eventId);
        });

        // Filter by status
        $query->when($filters['status'] ?? null, function ($q, $status) {
            return $q->where('status', $status);
        });

        // Filter by date range
        $query->when($filters['start_date'] ?? null, function ($q, $startDate) {
            return $q->whereDate('created_at', '>=', $startDate);
        });
        $query->when($filters['end_date'] ?? null, function ($q, $endDate) {
            return $q->whereDate('created_at', '<=', $endDate);
        });

        // Handle search filter
        $query->when($filters['search'] ?? null, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        });

        return $query->latest()->get();
    }
}
