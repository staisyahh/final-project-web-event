<?php

namespace App\Queries;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class LandingPageQuery
{
    /**
     * Mengambil semua event yang dipublikasikan dan dijadwalkan di masa depan.
     *
     * @return Collection
     */
    public function getPublishedEvents(): Collection
    {
        return Event::with(['category', 'organizer'])
            ->where('status', 'published')
            ->where('jadwal', '>=', now())
            ->orderBy('jadwal', 'asc')
            ->get();
    }

    /**
     * Mengambil semua kategori.
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Category::orderBy('name', 'asc')->get();
    }
}
