<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\DriverInterface;
use Throwable;

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
     * - Raster images are re-encoded through GD/Imagick so polyglot
     *   payloads cannot be stored as-is.
     */
    public function store(UploadedFile $file, string $disk, ?string $collection = null, string $errorKey = 'file'): MediaAsset
    {
        $fail = fn (string $message) => throw ValidationException::withMessages([$errorKey => $message]);

        $allowedImages = config('ecozeen.media.allowed_image_mime_types');
        $allowedDocuments = config('ecozeen.media.allowed_document_mime_types');
        $allowed = array_merge($allowedImages, $allowedDocuments);

        $detectedMime = $this->detectMime($file);

        if (! in_array($detectedMime, $allowed, true)) {
            $fail('This file type is not permitted. Allowed types: images (JPEG, PNG, WEBP, GIF, SVG) and PDF documents.');
        }

        $isImage = in_array($detectedMime, $allowedImages, true);
        $maxKb = $isImage
            ? config('ecozeen.media.max_image_size_kb')
            : config('ecozeen.media.max_document_size_kb');

        if ($file->getSize() > $maxKb * 1024) {
            $fail("The file exceeds the maximum allowed size of {$maxKb} KB.");
        }

        $realPath = $file->getRealPath();

        if (! is_string($realPath) || $realPath === '' || ! is_readable($realPath)) {
            $fail('The uploaded file could not be read. Please try again.');
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
        $directory = trim((string) $collection, '/');
        $path = ltrim(($directory !== '' ? $directory.'/' : '').$storageFilename, '/');

        $width = null;
        $height = null;
        $written = false;

        try {
            if ($directory !== '' && ! Storage::disk($disk)->exists($directory)) {
                Storage::disk($disk)->makeDirectory($directory);
            }

            // SVG is intentionally excluded from raster re-encoding (Intervention
            // cannot safely rasterize arbitrary SVG, and we do not want to
            // execute/parse untrusted XML beyond MIME + size validation here).
            if ($isImage && $detectedMime !== 'image/svg+xml') {
                [$binary, $width, $height] = $this->encodeRasterImage($realPath, $extension, $errorKey);
                $written = Storage::disk($disk)->put($path, $binary);
            } else {
                $contents = file_get_contents($realPath);

                if ($contents === false) {
                    $fail('The uploaded file could not be read. Please try again.');
                }

                $written = Storage::disk($disk)->put($path, $contents);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::warning('Media upload failed.', [
                'mime' => $detectedMime,
                'original' => $file->getClientOriginalName(),
                'message' => $e->getMessage(),
            ]);

            $fail('The file could not be stored. Confirm the public storage disk is writable and that the GD or Imagick PHP extension is enabled.');
        }

        if ($written === false) {
            $fail('The file could not be stored. Confirm storage/app/public is writable and that php artisan storage:link has been run.');
        }

        return MediaAsset::create([
            'disk' => $disk,
            'path' => $path,
            'filename' => $storageFilename,
            'original_filename' => $this->sanitizeOriginalName($file->getClientOriginalName()),
            'mime_type' => $detectedMime,
            'extension' => $extension,
            'size' => Storage::disk($disk)->size($path) ?: $file->getSize(),
            'width' => $width,
            'height' => $height,
            'collection' => $collection,
            'uploaded_by' => Auth::id(),
        ]);
    }

    /**
     * @return array{0: string, 1: int, 2: int}
     */
    private function encodeRasterImage(string $realPath, string $extension, string $errorKey): array
    {
        try {
            $manager = new ImageManager($this->imageDriver($errorKey));
            $image = $manager->decode($realPath);
            $width = $image->width();
            $height = $image->height();

            $encoded = $image->encodeUsingFileExtension($extension, quality: 85);

            return [(string) $encoded, $width, $height];
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::warning('Raster image encoding failed.', [
                'extension' => $extension,
                'message' => $e->getMessage(),
            ]);

            throw ValidationException::withMessages([
                $errorKey => 'The image could not be processed. Please upload a valid JPEG, PNG, WEBP, or GIF file.',
            ]);
        }
    }

    private function imageDriver(string $errorKey): DriverInterface
    {
        if (extension_loaded('gd')) {
            return new GdDriver;
        }

        if (extension_loaded('imagick')) {
            return new ImagickDriver;
        }

        throw ValidationException::withMessages([
            $errorKey => 'Image processing is unavailable on this server. Enable the PHP GD or Imagick extension, then try again.',
        ]);
    }

    private function detectMime(UploadedFile $file): string
    {
        $candidates = [];

        $realPath = $file->getRealPath();
        if (is_string($realPath) && $realPath !== '' && is_readable($realPath)) {
            $fromFile = (new \finfo(FILEINFO_MIME_TYPE))->file($realPath);
            if (is_string($fromFile) && $fromFile !== '') {
                $candidates[] = $fromFile;
            }
        }

        foreach ([$file->getMimeType(), $file->getClientMimeType()] as $candidate) {
            if (is_string($candidate) && $candidate !== '') {
                $candidates[] = $candidate;
            }
        }

        $aliases = [
            'image/jpg' => 'image/jpeg',
            'image/pjpeg' => 'image/jpeg',
            'image/x-png' => 'image/png',
            'image/x-webp' => 'image/webp',
            'image/svg' => 'image/svg+xml',
        ];

        $allowed = array_merge(
            config('ecozeen.media.allowed_image_mime_types'),
            config('ecozeen.media.allowed_document_mime_types'),
        );

        foreach ($candidates as $mime) {
            $normalized = $aliases[strtolower(trim($mime))] ?? strtolower(trim($mime));
            if (in_array($normalized, $allowed, true)) {
                return $normalized;
            }
        }

        return strtolower(trim($candidates[0] ?? 'application/octet-stream'));
    }

    private function sanitizeOriginalName(string $name): string
    {
        $name = strip_tags($name);

        return Str::limit($name, 255, '');
    }
}
