<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaUploadRequest;
use App\Models\MediaAsset;
use App\Services\ActivityLogger;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MediaAsset::class, 'medium');
    }

    public function index(): View
    {
        $media = MediaAsset::query()
            ->with('uploader')
            ->when(request('q'), fn ($q) => $q->where('original_filename', 'like', '%'.request('q').'%'))
            ->when(request('type') === 'image', fn ($q) => $q->where('mime_type', 'like', 'image/%'))
            ->when(request('type') === 'document', fn ($q) => $q->where('mime_type', 'application/pdf'))
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('admin.media.index', compact('media'));
    }

    public function store(MediaUploadRequest $request, MediaUploadService $uploader): RedirectResponse
    {
        $collection = $request->input('collection');
        $count = 0;

        foreach ($request->file('files') as $file) {
            $asset = $uploader->store($file, config('ecozeen.media.disk'), $collection);
            ActivityLogger::log('uploaded', $asset, "Uploaded media {$asset->original_filename}");
            $count++;
        }

        return redirect()->route('admin.media.index')->with('success', "{$count} file(s) uploaded successfully.");
    }

    public function update(Request $request, MediaAsset $medium): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
        ]);

        $medium->update($data);

        return back()->with('success', 'Media details updated.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
        $usageCount = $this->usageCount($medium);

        if ($usageCount > 0) {
            return back()->with('error', "This file is referenced by {$usageCount} other record(s) and cannot be deleted until those references are removed.");
        }

        \Illuminate\Support\Facades\Storage::disk($medium->disk)->delete($medium->path);
        $filename = $medium->original_filename;
        $medium->delete();

        ActivityLogger::log('deleted', null, "Deleted media {$filename}");

        return back()->with('success', 'Media deleted.');
    }

    private function usageCount(MediaAsset $medium): int
    {
        $foreignKeyTables = [
            'companies' => ['logo_media_id', 'cover_media_id', 'seo_image_media_id'],
            'products' => ['logo_media_id', 'screenshot_media_id', 'seo_image_media_id'],
            'services' => ['cover_media_id', 'seo_image_media_id'],
            'projects' => ['cover_media_id', 'seo_image_media_id'],
            'pages' => ['seo_image_media_id'],
            'blog_posts' => ['cover_media_id', 'seo_image_media_id'],
            'website_settings' => ['logo_media_id', 'logo_dark_media_id', 'favicon_media_id', 'default_og_image_media_id'],
        ];

        $count = 0;
        foreach ($foreignKeyTables as $table => $columns) {
            foreach ($columns as $column) {
                $count += \Illuminate\Support\Facades\DB::table($table)->where($column, $medium->id)->count();
            }
        }

        $count += \Illuminate\Support\Facades\DB::table('project_media')->where('media_asset_id', $medium->id)->count();

        return $count;
    }
}
