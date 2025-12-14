<?php

namespace App\Jobs;

use App\Mail\EventReminderMail;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class SendEventReminderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Registration $registration;

    /**
     * Create a new job instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration->load(['user', 'event', 'eTickets']);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->registration->user || !$this->registration->event) {
            return;
        }
        
        foreach ($this->registration->eTickets as $eTicket) {
            $qrCodePath = null;
            try {
                // 1. Generate QR Code and save temporarily
                $options = new QROptions([
                    'scale' => 10,
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel' => QRCode::ECC_L,
                ]);
                $qrCodeData = (new QRCode($options))->render($eTicket->ticket_code);
                $qrCodeBinary = base64_decode(preg_replace('#^data:image/png;base64,#i', '', $qrCodeData));

                $tempFileName = 'qrcodes/' . $eTicket->ticket_code . '.png';
                Storage::disk('local')->put($tempFileName, $qrCodeBinary);
                $qrCodePath = Storage::disk('local')->path($tempFileName);

                // 2. Send the email with the path
                Mail::to($this->registration->user->email)->send(
                    new EventReminderMail(
                        $this->registration->user,
                        $this->registration->event,
                        $eTicket,
                        $qrCodePath
                    )
                );
            } finally {
                // 3. Clean up the temporary file
                if ($qrCodePath && Storage::disk('local')->exists('qrcodes/' . basename($qrCodePath))) {
                    Storage::disk('local')->delete('qrcodes/' . basename($qrCodePath));
                }
            }
        }
    }
}
