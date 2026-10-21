<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'disk',
        'path',
        'filename',
        'original_filename',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'title',
        'alt_text',
        'caption',
        'collection',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Host-relative public URL that does not depend on APP_URL or a
     * storage symlink. Hostinger forbids HTTP access to /storage, so every
     * public image is addressed as /media/{path}.
     */
    public function url(): string
    {
        $path = ltrim((string) $this->path, '/');

        if ($path === '') {
            return asset('brand/ecozeen-mark.png');
        }

        return '/media/'.$path;
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function existsOnDisk(): bool
    {
        try {
            return Storage::disk($this->disk ?: config('ecozeen.media.disk'))->exists($this->path);
        } catch (\Throwable) {
            return is_file(public_path('media/'.ltrim((string) $this->path, '/')))
                || is_file(storage_path('app/public/'.ltrim((string) $this->path, '/')));
        }
    }

    public function humanSize(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1).' '.$units[$i];
    }
}
