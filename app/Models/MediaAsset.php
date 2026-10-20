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

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
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
