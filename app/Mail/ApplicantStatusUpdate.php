<?php

namespace App\Mail;

use App\Models\DriverApplication;
use App\Services\SettingsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicantStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public DriverApplication $application,
        public string $previousStatus,
    ) {}

    public function envelope(): Envelope
    {
        $companyName = app(SettingsService::class)->get('company_name', config('recruitment.company_name'));

        return new Envelope(
            subject: 'Application Update — '.$companyName,
        );
    }

    public function content(): Content
    {
        $statuses = DriverApplication::statuses();

        return new Content(
            markdown: 'emails.applicant-status-update',
            with: [
                'application' => $this->application,
                'companyName' => app(SettingsService::class)->get('company_name', config('recruitment.company_name')),
                'statusLabel' => $statuses[$this->application->status]['label'] ?? ucfirst($this->application->status),
                'previousStatusLabel' => $statuses[$this->previousStatus]['label'] ?? ucfirst($this->previousStatus),
            ],
        );
    }
}
