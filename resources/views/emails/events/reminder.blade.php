<x-mail::message>
# Halo, {{ $user->name }}!

Terima kasih telah mendaftar untuk event **{{ $event->title }}**. Kami sangat menantikan kehadiran Anda!

<p style="text-align: center; font-size: 1.1em; font-weight: bold; margin-bottom: 20px;">
    Event Anda akan segera berlangsung!
</p>

## Detail Event Anda:
-   **Nama Event:** {{ $event->title }}
-   **Jadwal:** {{ $event->jadwal->format('l, d F Y') }} pukul {{ $event->jadwal->format('H:i') }} WIB
-   **Lokasi:** {{ $event->location_name }}
@if ($event->location_address)
-   **Alamat:** {{ $event->location_address }}
@endif

---

## E-Ticket Anda:
Ini adalah E-Ticket Anda untuk event **{{ $event->title }}**. Mohon simpan baik-baik dan tunjukkan saat registrasi di lokasi event.

<div style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
    <img src="{{ $message->embed($qrCodePath) }}" alt="QR Code" style="width: 200px; height: 200px;">
    <p style="font-family: monospace; font-size: 1.2em; font-weight: bold; letter-spacing: 2px;">{{ $eTicket->ticket_code }}</p>
</div>

---

<x-mail::button :url="route('event.detail', $event->slug)">
Lihat Detail Event
</x-mail::button>

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.

Terima kasih,<br>
Tim {{ config('app.name') }}
</x-mail::message>
