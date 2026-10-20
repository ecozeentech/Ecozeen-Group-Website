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
    protected function resolveMediaId(Request $request, string $field, ?string $collection = null): ?int
    {
        if ($request->hasFile("{$field}_upload")) {
            $asset = app(MediaUploadService::class)->store(
                $request->file("{$field}_upload"),
                config('ecozeen.media.disk'),
                $collection
            );

            return $asset->id;
        }

        $value = $request->input($field);

        return $value !== null && $value !== '' ? (int) $value : null;
    }
}
