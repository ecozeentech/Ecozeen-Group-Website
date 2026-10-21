<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PublicMediaController extends Controller
{
    /**
     * Serve a public media file without depending on a storage symlink.
     *
     * Hostinger's repo-root .htaccess forbids HTTP access to /storage, so
     * files must be reachable at /media/{path}. New uploads live on the
     * public disk (public/media). Older files that were stored under
     * storage/app/public are still served from there as a fallback.
     */
    public function __invoke(string $path): Response
    {
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..') || preg_match('#^[A-Za-z0-9][A-Za-z0-9._/-]*$#', $path) !== 1) {
            abort(404);
        }

        $disk = config('ecozeen.media.disk', 'public');

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->response($path, headers: [
                'Cache-Control' => 'public, max-age=604800, immutable',
            ]);
        }

        $legacyRoot = realpath(storage_path('app/public'));
        $legacyFile = $legacyRoot ? realpath($legacyRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path)) : false;

        if ($legacyRoot && $legacyFile && str_starts_with($legacyFile, $legacyRoot) && is_file($legacyFile)) {
            return response()->file($legacyFile, [
                'Cache-Control' => 'public, max-age=604800, immutable',
            ]);
        }

        abort(404);
    }
}
