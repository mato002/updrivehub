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
                'adminUrl' => route('admin.applications.show', $this->application),
            ],
        );
    }

    public function attachments(): array
    {
        $storage = app(DocumentStorageService::class);
        $attachments = [];
        $totalSize = 0;
        $maxAttachBytes = 8 * 1024 * 1024;

        foreach ($this->application->documentPaths() as $path) {
            if (! $storage->exists($path)) {
                continue;
            }

            $disk = $storage->diskForPath($path);
            $size = Storage::disk($disk)->size($path);

            if ($totalSize + $size > $maxAttachBytes) {
                continue;
            }

            $attachments[] = Attachment::fromStorageDisk($disk, $path)
                ->as(basename($path))
                ->withMime($storage->mimeType($path));

            $totalSize += $size;
        }

        return $attachments;
    }
}
