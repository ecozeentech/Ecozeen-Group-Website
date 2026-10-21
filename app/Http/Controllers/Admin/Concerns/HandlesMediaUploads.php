<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\MediaUploadService;
use Illuminate\Http\Request;

/**
 * Lets any admin form field double as a "pick from the media library OR
 * upload a brand-new file" control without a heavier JS media-manager
 * component. See resources/views/components/admin/media-picker.blade.php.
 */
trait HandlesMediaUploads
{
    /**
     * Resolve a media FK from the request.
     *
     * - A new upload wins.
     * - An explicit empty value (the "Remove" control) clears the FK.
     * - When the field is omitted (upload mode with no file, or a disabled
     *   control), the existing ID is kept so a routine save cannot wipe images.
     */
    protected function resolveMediaId(Request $request, string $field, ?string $collection = null, ?int $fallback = null): ?int
    {
        if ($request->hasFile("{$field}_upload")) {
            $asset = app(MediaUploadService::class)->store(
                $request->file("{$field}_upload"),
                config('ecozeen.media.disk'),
                $collection,
                "{$field}_upload",
            );

            return $asset->id;
        }

        if (! $request->exists($field)) {
            return $fallback;
        }

        $value = $request->input($field);

        return $value !== null && $value !== '' ? (int) $value : null;
    }

    /**
     * Section builders store image FKs inside the settings JSON. The media
     * picker posts a sibling field (image_media_id / image_media_id_upload)
     * so an upload is not trapped inside a nested settings[image_media_id]
     * name that PHP cannot bind to a file input.
     */
    protected function mergeSectionImageMedia(Request $request, array $settings, ?int $currentImageId = null): array
    {
        if ($request->hasFile('image_media_id_upload') || $request->exists('image_media_id')) {
            $settings['image_media_id'] = $this->resolveMediaId(
                $request,
                'image_media_id',
                'pages',
                $currentImageId
            );
        }

        return $settings;
    }
}
