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

class Product extends Model
{
    use HasFactory, HasPublishingStatus, HasSeoFields, HasSlug, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'tagline',
        'short_description',
        'description',
        'logo_media_id',
        'screenshot_media_id',
        'website_url',
        'features',
        'metrics',
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
            'features' => 'array',
            'metrics' => 'array',
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product');
    }

    public function logoMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'logo_media_id');
    }

    public function screenshotMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'screenshot_media_id');
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
