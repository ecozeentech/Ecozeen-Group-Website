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

class Service extends Model
{
    use HasFactory, HasPublishingStatus, HasSeoFields, HasSlug, SoftDeletes;

    protected $fillable = [
        'service_category_id',
        'name',
        'slug',
        'summary',
        'description',
        'icon',
        'cover_media_id',
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
            'description' => PurifyHtmlOnSet::class,
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->usingSeparator('-');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_service');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_service');
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
