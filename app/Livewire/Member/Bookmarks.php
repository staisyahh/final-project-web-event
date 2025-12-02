<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class Bookmarks extends Component
{
    use WithPagination;

    public function toggleBookmark(int $eventId)
    {
        // The toggle action will automatically detach the bookmark.
        Auth::user()->bookmarkedEvents()->toggle($eventId);

        // We don't need to explicitly refresh, as Livewire's next render
        // will fetch the updated list.
    }

    public function render()
    {
        $bookmarkedEvents = Auth::user()
            ->bookmarkedEvents()
            ->with('category')
            ->latest('bookmarks.created_at') // Order by when it was bookmarked
            ->paginate(12);

        return view('livewire.member.bookmarks', [
            'events' => $bookmarkedEvents,
        ])->title('Event Favorit Saya');
    }
}
