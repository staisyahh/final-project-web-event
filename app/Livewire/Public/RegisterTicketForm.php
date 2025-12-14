<?php

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;
use App\Models\Registration;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RegisterTicketForm extends Component
{
    public Event $event;
    public string $eventSlug;
    public $tickets; // Available tickets for the event

    #[Validate('required|exists:tickets,id')]
    public ?int $selectedTicketId = null;

    #[Validate('required|integer|min:1')]
    public int $quantity = 1;

    public float $totalPrice = 0;
    public string $errorMessage = '';

    public function mount(int $eventId, string $eventSlug)
    {
        $this->event = Event::with(['tickets' => function ($query) {
            $query->where('stok', '>', 0)->where('status', 'available');
        }])->findOrFail($eventId);

        $this->tickets = $this->event->tickets;
        $this->eventSlug = $eventSlug;

        if ($this->tickets->isNotEmpty()) {
            $this->selectedTicketId = $this->tickets->first()->id;
            $this->calculateTotalPrice();
        }
    }

    public function updatedSelectedTicketId(): void
    {
        $this->calculateTotalPrice();
    }

    public function updatedQuantity(): void
    {
        $this->calculateTotalPrice();
    }

    private function calculateTotalPrice(): void
    {
        if ($this->selectedTicketId) {
            $selectedTicket = $this->tickets->where('id', $this->selectedTicketId)->first();
            if ($selectedTicket) {
                $this->totalPrice = $selectedTicket->harga * $this->quantity;
            } else {
                $this->totalPrice = 0;
            }
        } else {
            $this->totalPrice = 0;
        }
    }

    public function register()
    {
        if (!Auth::check()) {
            $this->dispatch('close-modal');
            return redirect()->route('login');
        }

        $this->resetErrorBag();
        $this->errorMessage = '';

        try {
            $this->validate();

            DB::beginTransaction();

            $selectedTicket = Ticket::where('id', $this->selectedTicketId)
                ->where('event_id', $this->event->id)
                ->where('stok', '>=', $this->quantity)
                ->where('status', 'available')
                ->lockForUpdate() // Lock the ticket row
                ->first();

            if (!$selectedTicket) {
                DB::rollBack();
                $this->errorMessage = 'Tiket yang dipilih tidak valid atau stok tidak mencukupi.';
                return;
            }

            // Create Registration
            Registration::create([
                'user_id' => Auth::id(),
                'ticket_id' => $selectedTicket->id,
                'event_id' => $this->event->id,
                'jumlah_tiket' => $this->quantity,
                'total_bayar' => $this->totalPrice,
                'status' => 'pending_payment', // Initial status
            ]);

            // Decrement stock
            $selectedTicket->stok -= $this->quantity;
            if ($selectedTicket->stok === 0) {
                $selectedTicket->status = 'sold_out';
            }
            $selectedTicket->save();

            DB::commit();

            session()->flash('success', 'Pendaftaran berhasil! Silakan selesaikan pembayaran Anda.');
            $this->dispatch('close-modal');
            return redirect()->route('member.tiket');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            $this->errorMessage = 'Mohon periksa kembali input Anda.';
            $this->dispatch('error-message', message: 'Validasi gagal. Cek kembali input Anda.');
            throw $e; // Re-throw to show validation errors
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.';
            $this->dispatch('error-message', message: $e->getMessage()); // Debugging
            Log::error("Registration error: " . $e->getMessage(), ['exception' => $e]);
        }
    }

    public function render()
    {
        return view('livewire.public.register-ticket-form');
    }
}
