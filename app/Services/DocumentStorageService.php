<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    private const PRIVATE_DISK = 'local';

    private const LEGACY_DISK = 'public';

    public function store(UploadedFile $file, string $prefix = 'document'): string
    {
        $directory = 'driver_documents/'.now()->format('Y/m');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $prefix.'_'.now()->format('YmdHis').'_'.Str::uuid().'.'.$extension;

        $path = $file->storeAs($directory, $filename, self::PRIVATE_DISK);

        if ($path === false) {
            throw new \RuntimeException('Failed to store uploaded document.');
        }

        return $path;
    }

    public function diskForPath(string $relativePath): string
    {
        if (Storage::disk(self::PRIVATE_DISK)->exists($relativePath)) {
            return self::PRIVATE_DISK;
        }

        return self::LEGACY_DISK;
    }

    public function exists(string $relativePath): bool
    {
        return Storage::disk($this->diskForPath($relativePath))->exists($relativePath);
    }

    public function absolutePath(string $relativePath): string
    {
        $disk = $this->diskForPath($relativePath);

        return Storage::disk($disk)->path($relativePath);
    }

    public function mimeType(string $relativePath): string
    {
        $disk = $this->diskForPath($relativePath);

        return Storage::disk($disk)->mimeType($relativePath) ?: 'application/octet-stream';
    }

    public function downloadResponse(string $relativePath, string $filename)
    {
        $disk = $this->diskForPath($relativePath);

        return Storage::disk($disk)->download($relativePath, $filename);
    }

    public function url(string $relativePath): ?string
    {
        if ($this->diskForPath($relativePath) === self::LEGACY_DISK) {
            return Storage::disk(self::LEGACY_DISK)->url($relativePath);
        }

        return null;
    }
}
