<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManager;

class MediaUploadService
{
    /**
     * Securely store an uploaded file as a MediaAsset.
     *
     * Security notes:
     * - The MIME type and extension are derived from the file's actual
     *   detected content (Symfony/finfo), never the client-supplied name.
     * - A random, non-guessable storage filename is generated; the
     *   original filename is only ever used for display.
     * - Only an explicit allow-list of MIME types is accepted.
     */
    public function store(UploadedFile $file, string $disk, ?string $collection = null): MediaAsset
    {
        $allowedImages = config('ecozeen.media.allowed_image_mime_types');
        $allowedDocuments = config('ecozeen.media.allowed_document_mime_types');
        $allowed = array_merge($allowedImages, $allowedDocuments);

        $detectedMime = $file->getMimeType();

        if (! in_array($detectedMime, $allowed, true)) {
            throw ValidationException::withMessages([
                'file' => 'This file type is not permitted. Allowed types: images (JPEG, PNG, WEBP, GIF, SVG) and PDF documents.',
            ]);
        }

        $isImage = in_array($detectedMime, $allowedImages, true);
        $maxKb = $isImage
            ? config('ecozeen.media.max_image_size_kb')
            : config('ecozeen.media.max_document_size_kb');

        if ($file->getSize() > $maxKb * 1024) {
            throw ValidationException::withMessages([
                'file' => "The file exceeds the maximum allowed size of {$maxKb} KB.",
            ]);
        }

        $extension = match ($detectedMime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'application/pdf' => 'pdf',
            default => 'bin',
        };

        $storageFilename = Str::uuid()->toString().'.'.$extension;
        $path = trim(($collection ? $collection.'/' : '').$storageFilename, '/');

        $width = null;
        $height = null;

        // SVG is intentionally excluded from raster re-encoding (Intervention
        // cannot safely rasterize arbitrary SVG, and we do not want to
        // execute/parse untrusted XML beyond MIME + size validation here).
        if ($isImage && $detectedMime !== 'image/svg+xml') {
            $manager = new ImageManager('gd');
            $image = $manager->decodePath($file->getRealPath());
            $width = $image->width();
            $height = $image->height();
            Storage::disk($disk)->put($path, (string) $image->encodeUsingFileExtension($extension, quality: 85));
        } else {
            Storage::disk($disk)->put($path, file_get_contents($file->getRealPath()));
        }

        return MediaAsset::create([
            'disk' => $disk,
            'path' => $path,
            'filename' => $storageFilename,
            'original_filename' => $this->sanitizeOriginalName($file->getClientOriginalName()),
            'mime_type' => $detectedMime,
            'extension' => $extension,
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'collection' => $collection,
            'uploaded_by' => Auth::id(),
        ]);
    }

    private function sanitizeOriginalName(string $name): string
    {
        $name = strip_tags($name);

        return Str::limit($name, 255, '');
    }
}
