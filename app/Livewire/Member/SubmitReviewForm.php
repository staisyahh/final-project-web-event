<?php

namespace App\Livewire\Member;

use App\Models\Event;
use App\Models\Review;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class SubmitReviewForm extends Component
{
    public ?Event $event = null;

    #[Validate('required|integer|min:1|max:5')]
    public int $rating = 0;

    #[Validate('nullable|string|max:1000')]
    public string $comment = '';

    public bool $modalOpen = false;

    #[On('open-review-modal')]
    public function openModal(int $eventId)
    {
        $this->event = Event::findOrFail($eventId);
        
        // Load existing review if any
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('event_id', $this->event->id)
                                ->first();

        if ($existingReview) {
            $this->rating = $existingReview->rating;
            $this->comment = $existingReview->comment;
        } else {
            $this->reset(['rating', 'comment']);
        }
        
        $this->resetValidation();
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->dispatch('close-modal');
        $this->dispatch('review-submitted'); // Notify parent to refresh
    }

    public function saveReview()
    {
        $this->validate();

        if (!$this->event) {
            session()->flash('error', 'Event tidak ditemukan.');
            $this->closeModal();
            return;
        }

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'event_id' => $this->event->id,
            ],
            [
                'rating' => $this->rating,
                'comment' => $this->comment,
            ]
        );

        session()->flash('success', 'Terima kasih atas ulasan Anda!');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.member.submit-review-form');
    }
}
