<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    public function store(UploadedFile $file, string $prefix = 'document'): string
    {
        $directory = 'driver_documents/'.now()->format('Y/m');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $prefix.'_'.now()->format('YmdHis').'_'.Str::uuid().'.'.$extension;

        $path = $file->storeAs($directory, $filename, 'public');

        if ($path === false) {
            throw new \RuntimeException('Failed to store uploaded document.');
        }

        return $path;
    }

    public function absolutePath(string $relativePath): string
    {
        return Storage::disk('public')->path($relativePath);
    }

    public function url(string $relativePath): string
    {
        return Storage::disk('public')->url($relativePath);
    }
}
