<?php

namespace App\Mail;

use App\Models\DriverApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicantConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DriverApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.applicant-confirmation',
            with: [
                'application' => $this->application,
                'companyName' => config('recruitment.company_name'),
            ],
        );
    }
}
