<?php

namespace App\Livewire;

use App\Queries\LandingPageQuery;
use App\Services\LandingPageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

#[Layout('components.layouts.front')]
#[Title('Beranda - Web Event')]
class LandingPage extends Component
{
    // 1. Definisikan sebagai public property
    public array $events = [];
    public array $categories = [];

    public function mount(LandingPageQuery $landingPageQuery)
    {
        // 2. Isi data saat komponen pertama kali dimuat (Mounting)
        // Kita ubah ke array agar lebih ringan dan mudah dibaca Alpine.js
        $this->categories = $landingPageQuery->getCategories()->toArray();
    }

    public function toggleBookmark(int $eventId)
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        Auth::user()->bookmarkedEvents()->toggle($eventId);
        $this->dispatch('bookmark-toggled');
    }

    #[On('bookmark-toggled')]
    public function refreshEvents()
    {
        // Event handler
    }

    public function render(LandingPageService $landingPageService)
    {
        // 3. Isi property events
        $this->events = $landingPageService->getEventsForHomepage()->all();

        // 4. Tidak perlu lagi mengirim ['categories' => ...] di sini
        // karena $this->categories otomatis tersedia di view.
        return view('livewire.landing-page');
    }
}
