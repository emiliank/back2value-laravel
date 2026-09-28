<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $companyName,
        public string $contactName,
        public string $email,
        public string $phone,
        public string $sector,
        public int $fleetSize,
        public string $requirements,
        public ?string $message = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'sales@back2value.com',
            to: ['sales@back2value.com'],
            subject: 'New B2B Maintenance Inquiry - '.$this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-inquiry',
            with: [
                'companyName' => $this->companyName,
                'contactName' => $this->contactName,
                'email' => $this->email,
                'phone' => $this->phone,
                'sector' => $this->sector,
                'fleetSize' => $this->fleetSize,
                'requirements' => $this->requirements,
                'message' => $this->message,
            ],
        );
    }
}
