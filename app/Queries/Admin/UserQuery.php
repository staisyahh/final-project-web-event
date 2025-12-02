<?php

namespace App\Queries\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserQuery
{
    /**
     * Get paginated users with filters.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::query();

        // Filter by role
        $query->when($filters['role'] ?? null, function ($q, $role) {
            return $q->where('role', $role);
        });

        // Handle search filter
        $query->when($filters['search'] ?? null, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        });

        return $query->latest()->paginate($perPage);
    }
}
