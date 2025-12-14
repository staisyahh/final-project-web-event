<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use App\Models\ETicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EventReminderMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public User $user;
    public Event $event;
    public ETicket $eTicket;
    public string $qrCodePath;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Event $event, ETicket $eTicket, string $qrCodePath)
    {
        $this->user = $user;
        $this->event = $event;
        $this->eTicket = $eTicket;
        $this->qrCodePath = $qrCodePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat Event: ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.events.reminder',
            with: [
                'user' => $this->user,
                'event' => $this->event,
                'eTicket' => $this->eTicket,
                'qrCodePath' => $this->qrCodePath, // Pass the path to the view
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

