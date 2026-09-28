<?php

namespace App\Mail;

use App\Models\DiagnosticBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DiagnosticBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DiagnosticBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'sales@back2value.com',
            to: ['sales@back2value.com'],
            subject: 'New Battery Diagnostic Booking - '.$this->booking->customer_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.diagnostic-booking',
            with: [
                'booking' => $this->booking,
            ],
        );
    }
}
