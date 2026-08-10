<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverApplication;
use App\Services\DocumentStorageService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function show(DriverApplication $application, string $document, DocumentStorageService $documentStorage): Response|StreamedResponse
    {
        $path = $application->pathForDocument($document);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'Document not found.');
        }

        $absolutePath = $documentStorage->absolutePath($path);
        $filename = basename($path);
        $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream';

        return response()->file($absolutePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function download(DriverApplication $application, string $document, DocumentStorageService $documentStorage): StreamedResponse
    {
        $path = $application->pathForDocument($document);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404, 'Document not found.');
        }

        $label = config("recruitment.document_fields.{$document}.label", $document);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = str($label)->slug().'.'.$extension;

        return Storage::disk('public')->download($path, $filename);
    }
}
