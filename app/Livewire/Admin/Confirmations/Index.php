<?php

namespace App\Livewire\Admin\Confirmations;

use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public function confirmPayment(int $registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        if ($registration->status === 'pending_payment') {
            
            // TODO: Implement e-ticket creation logic here
            // This should be moved from RegisterTicketForm to here, or better, into an event listener.
            
            $registration->status = 'confirmed';
            $registration->save();

            // Trigger e-ticket creation
            \App\Events\RegistrationConfirmed::dispatch($registration);

            session()->flash('success', 'Pembayaran berhasil dikonfirmasi.');
        } else {
            session()->flash('error', 'Status registrasi sudah bukan pending payment.');
        }
    }

    public function rejectPayment(int $registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        if ($registration->status === 'pending_payment') {
            $registration->status = 'cancelled';
            $registration->save();

            // TODO: Optionally, delete the uploaded proof file.
            // Storage::disk('public')->delete($registration->payment_proof_path);

            session()->flash('success', 'Pembayaran ditolak.');
        } else {
            session()->flash('error', 'Status registrasi sudah bukan pending payment.');
        }
    }
    
    public function render()
    {
        $pendingRegistrations = Registration::query()
            ->where('status', 'pending_payment')
            ->whereNotNull('payment_proof_path')
            ->with(['user', 'event'])
            ->latest()
            ->paginate(10);
            
        return view('livewire.admin.confirmations.index', [
            'registrations' => $pendingRegistrations,
        ])->title('Konfirmasi Pembayaran');
    }
}
