<?php

namespace App\Queries\Admin;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewQuery
{
    /**
     * Get paginated reviews with optional filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Review::query()
            ->with(['user', 'event'])
            ->latest();

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('comment', 'like', $searchTerm)
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('event', function ($eventQuery) use ($searchTerm) {
                        $eventQuery->where('title', 'like', $searchTerm);
                    });
            });
        }

        return $query->paginate($perPage);
    }
}
