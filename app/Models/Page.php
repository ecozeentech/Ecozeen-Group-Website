<?php

namespace App\Models;

use App\Models\Concerns\HasSeoFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasFactory, HasSeoFields, HasSlug, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'template',
        'status',
        'published_at',
        'scheduled_at',
        'is_homepage',
        'seo_title',
        'seo_description',
        'canonical_url',
        'seo_image_media_id',
        'no_index',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'is_homepage' => 'boolean',
            'no_index' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->usingSeparator('-');
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('position');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function seoImageMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'seo_image_media_id');
    }

    public function revisions()
    {
        return $this->morphMany(ContentRevision::class, 'revisionable')->latest('created_at');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function isPubliclyVisible(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->published_at && Carbon::parse($this->published_at)->isFuture()) {
            return false;
        }

        return true;
    }
}
