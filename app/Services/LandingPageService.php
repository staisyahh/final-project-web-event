<?php

namespace App\Services;

use App\Queries\LandingPageQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class LandingPageService
{
    protected LandingPageQuery $landingPageQuery;

    public function __construct(LandingPageQuery $landingPageQuery)
    {
        $this->landingPageQuery = $landingPageQuery;
    }

    /**
     * Mengambil dan mentransformasi data event untuk homepage.
     *
     * @return Collection
     */
    public function getEventsForHomepage(): Collection
    {
        $events = $this->landingPageQuery->getPublishedEvents();

        // Ambil ID event yang di-bookmark oleh user saat ini, jika login
        $bookmarkedEventIds = Auth::check()
            ? Auth::user()->bookmarkedEvents()->pluck('events.id')->flip()
            : collect();

        // Transformasi data agar sesuai dengan struktur yg diharapkan Alpine.js
        return $events->map(function ($event) use ($bookmarkedEventIds) {
            // Ambil harga tiket terendah untuk ditampilkan
            $lowestPrice = $event->tickets()->min('harga') ?? 0;

            return [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'date_day' => $event->jadwal->format('d'),
                'date_month' => strtoupper($event->jadwal->format('M')),
                'location' => $event->location_name ?? 'Online',
                'image' => $event->banner_url ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80',
                'category' => $event->category->name ?? 'Lainnya',
                'price' => (float) $lowestPrice,
                'organizer' => $event->organizer->name ?? 'Penyelenggara',
                'is_bookmarked' => $bookmarkedEventIds->has($event->id), // Tambahkan status bookmark
            ];
        });
    }
}
