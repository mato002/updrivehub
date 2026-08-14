<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use App\Services\DocumentStorageService;
use App\Models\DriverApplication;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function show(
        DriverApplication $application,
        string $document,
        DocumentStorageService $documentStorage,
        ActivityLogger $activityLogger,
    ): Response|StreamedResponse {
        $path = $application->pathForDocument($document);

        if (! $path || ! $documentStorage->exists($path)) {
            abort(404, 'Document not found.');
        }

        $activityLogger->log(
            $application,
            'document_viewed',
            'Viewed document: '.config("recruitment.document_fields.{$document}.label", $document),
            auth()->user(),
            ['document' => $document],
        );

        $absolutePath = $documentStorage->absolutePath($path);
        $filename = basename($path);

        return response()->file($absolutePath, [
            'Content-Type' => $documentStorage->mimeType($path),
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function download(
        DriverApplication $application,
        string $document,
        DocumentStorageService $documentStorage,
        ActivityLogger $activityLogger,
    ): StreamedResponse {
        $path = $application->pathForDocument($document);

        if (! $path || ! $documentStorage->exists($path)) {
            abort(404, 'Document not found.');
        }

        $activityLogger->log(
            $application,
            'document_downloaded',
            'Downloaded document: '.config("recruitment.document_fields.{$document}.label", $document),
            auth()->user(),
            ['document' => $document],
        );

        $label = config("recruitment.document_fields.{$document}.label", $document);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = str($label)->slug().'.'.$extension;

        return $documentStorage->downloadResponse($path, $filename);
    }
}
