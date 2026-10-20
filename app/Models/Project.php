<?php

namespace App\Models;

use App\Models\Concerns\HasPublishingStatus;
use App\Models\Concerns\HasSeoFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stevebauman\Purify\Casts\PurifyHtmlOnSet;

class Project extends Model
{
    use HasFactory, HasPublishingStatus, HasSeoFields, HasSlug, SoftDeletes;

    protected $fillable = [
        'company_id',
        'project_category_id',
        'title',
        'slug',
        'client',
        'summary',
        'description',
        'outcomes',
        'cover_media_id',
        'completed_at',
        'status',
        'is_featured',
        'sort_order',
        'seo_title',
        'seo_description',
        'seo_image_media_id',
        'no_index',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'no_index' => 'boolean',
            'completed_at' => 'date',
            'description' => PurifyHtmlOnSet::class,
            'outcomes' => PurifyHtmlOnSet::class,
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'project_service');
    }

    public function galleryMedia()
    {
        return $this->belongsToMany(MediaAsset::class, 'project_media', 'project_id', 'media_asset_id')
            ->withPivot('sort_order')
            ->orderBy('project_media.sort_order');
    }

    public function coverMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'cover_media_id');
    }

    public function seoImageMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'seo_image_media_id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
