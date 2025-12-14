<?php

namespace App\Livewire\Member;

use App\Models\Registration;
use App\Models\ETicket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function downloadTicket(ETicket $eTicket)
    {
        // Pastikan tiket ini milik user yang sedang login
        if ($eTicket->registration->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        // Atur opsi untuk QR Code yang lebih besar
        $options = new QROptions([
            'scale' => 15, // Tingkatkan skala gambar QR Code
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
        ]);

        // Generate QR Code sebagai base64
        $qrCode = (new QRCode($options))->render($eTicket->ticket_code);

        // Siapkan data untuk view PDF
        $data = [
            'eTicket' => $eTicket,
            'registration' => $eTicket->registration,
            'event' => $eTicket->event,
            'ticket' => $eTicket->registration->ticket,
            'user' => $eTicket->registration->user,
            'qrCodePath' => $qrCode,
        ];

        // Buat PDF
        $pdf = Pdf::loadView('pdf.e-ticket', $data);

        // Download PDF
        $fileName = 'e-ticket-' . $eTicket->event->slug . '-' . $eTicket->ticket_code . '.pdf';
        return response()->streamDownload(
            fn () => print($pdf->output()),
            $fileName
        );
    }

    #[On('review-submitted')]
    public function refreshOnReviewSubmit()
    {
        // Placeholder method to trigger re-render
    }

    public function render()
    {
        $registrations = Registration::where('user_id', Auth::id())
            ->whereHas('event') // Pastikan event masih ada (tidak di soft-delete)
            ->with(['event.userReview', 'ticket', 'eTickets'])
            ->latest()
            ->get();

        return view('livewire.member.tiket-saya', [
            'registrations' => $registrations
        ]);
    }
}
