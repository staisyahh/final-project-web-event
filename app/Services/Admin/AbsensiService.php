<?php

namespace App\Services\Admin;

use App\Models\ETicket;

class AbsensiService
{
    /**
     * Process a check-in attempt for a given QR code and event.
     *
     * @param string $qrCode
     * @param int $eventId
     * @return array ['status' => 'success|warning|error', 'message' => '...', 'data' => []]
     */
    public function processCheckIn(string $qrCode, int $eventId): array
    {
        $eTicket = ETicket::where('ticket_code', $qrCode)->with('registration.user')->first();

        // Case 1: Ticket not found
        if (!$eTicket) {
            return [
                'status' => 'error',
                'message' => 'Tiket Tidak Valid / Tidak Ditemukan.',
                'data' => null,
            ];
        }

        // Case 2: Ticket is for a different event
        if ($eTicket->event_id !== $eventId) {
            return [
                'status' => 'error',
                'message' => 'Tiket ini bukan untuk event yang dipilih.',
                'data' => null,
            ];
        }

        // Case 3: Ticket has already been used
        if ($eTicket->status === 'checked_in') {
            return [
                'status' => 'warning',
                'message' => 'Tiket Sudah Digunakan. Nama: ' . $eTicket->registration->user->name,
                'data' => $eTicket->toArray(),
            ];
        }

        // Case 4: Success
        if ($eTicket->status === 'active') {
            $eTicket->status = 'checked_in';
            // Optionally, add a timestamp for check-in if the schema supports it
            // $eTicket->checked_in_at = now();
            $eTicket->save();

            return [
                'status' => 'success',
                'message' => 'Check-in Berhasil! Selamat datang, ' . $eTicket->registration->user->name . '.',
                'data' => $eTicket->toArray(),
            ];
        }
        
        // Default fallback case (should not be reached)
        return [
            'status' => 'error',
            'message' => 'Terjadi kesalahan yang tidak diketahui.',
            'data' => null,
        ];
    }
}
