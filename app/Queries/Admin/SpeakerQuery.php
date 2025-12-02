<?php

namespace App\Queries\Admin;

use App\Models\Speaker;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SpeakerQuery
{
    public function getPaginated(string $search = '', int $perPage = 10): LengthAwarePaginator
    {
        return Speaker::where('name', 'like', '%' . $search . '%')
            ->orWhere('title', 'like', '%' . $search . '%')
            ->latest()
            ->paginate($perPage);
    }
}
