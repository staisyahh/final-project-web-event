<?php

namespace App\Livewire\Member;

use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads; // Tambahkan ini
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.app')]
class TiketSaya extends Component
{
    use WithFileUploads; // Tambahkan ini

    public ?int $selectedRegistrationId = null;
    public ?int $selectedEventForReviewId = null;

    #[Validate('required|image|max:2048')] // Max 2MB
    public $paymentProofFile;

    public function selectRegistrationForUpload(int $registrationId)
    {
        $this->selectedRegistrationId = $registrationId;
        $this->resetValidation();
        $this->reset('paymentProofFile');
        $this->dispatch('open-modal', name: 'upload-proof-modal');
    }

    public function selectEventForReview(int $eventId)
    {
        $this->selectedEventForReviewId = $eventId;
        $this->dispatch('open-modal', name: 'review-modal');
        $this->dispatch('open-review-modal', eventId: $eventId);
    }

    public function savePaymentProof()
    {
        $this->validate();

        if ($this->selectedRegistrationId) {
            $registration = Registration::findOrFail($this->selectedRegistrationId);

            $path = $this->paymentProofFile->store('payment_proofs', 'public');

            $registration->payment_proof_path = $path;
            $registration->save();
            $this->dispatch('close-modal', name: 'upload-proof-modal');
                    $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => 'Bukti Pembayaran Berhasil Diunggah!',
        ]);
        } else {
            session()->flash('error', 'Terjadi kesalahan: Registrasi tidak ditemukan.');
            $this->dispatch('close-modal', name: 'upload-proof-modal');
        }
    }

    #[On('review-submitted')]
    public function refreshOnReviewSubmit()
    {
        // Placeholder method to trigger re-render
    }

    public function render()
    {
        $registrations = Registration::where('user_id', Auth::id())
            ->with(['event.userReview', 'ticket', 'eTickets']) // Eager load user's review for the event
            ->latest()
            ->get();

        return view('livewire.member.tiket-saya', [
            'registrations' => $registrations
        ]);
    }
}
