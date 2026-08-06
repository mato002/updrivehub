<?php

namespace App\Mail;

use App\Models\DriverApplication;
use App\Services\DocumentStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class HrApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DriverApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Driver Application Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hr-notification',
            with: [
                'application' => $this->application,
                'documentLinks' => $this->documentLinks(),
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        $totalSize = 0;
        $maxAttachBytes = 8 * 1024 * 1024;

        foreach ($this->application->documentPaths() as $label => $path) {
            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            $size = Storage::disk('public')->size($path);

            if ($totalSize + $size > $maxAttachBytes) {
                continue;
            }

            $attachments[] = Attachment::fromStorageDisk('public', $path)
                ->as(basename($path))
                ->withMime(Storage::disk('public')->mimeType($path));

            $totalSize += $size;
        }

        return $attachments;
    }

    protected function documentLinks(): array
    {
        $storage = app(DocumentStorageService::class);
        $links = [];

        foreach ($this->application->documentPaths() as $label => $path) {
            $links[] = [
                'label' => $label,
                'url' => $storage->url($path),
            ];
        }

        return $links;
    }
}
