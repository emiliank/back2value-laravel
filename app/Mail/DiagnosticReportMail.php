<?php

namespace App\Mail;

use App\Models\DiagnosticReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DiagnosticReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DiagnosticReport $report) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'sales@back2value.com',
            to: ['sales@back2value.com'],
            subject: 'Diagnostic report: reactivation eligible battery',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.diagnostic-report',
            with: [
                'report' => $this->report,
            ],
        );
    }
}
