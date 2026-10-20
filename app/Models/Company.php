<?php

namespace App\Models;

use App\Models\Concerns\HasSeoFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stevebauman\Purify\Casts\PurifyHtmlOnSet;

class Company extends Model
{
    use HasFactory, HasSeoFields, HasSlug, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'type',
        'parent_company_id',
        'category',
        'region',
        'website_url',
        'short_description',
        'description',
        'logo_media_id',
        'cover_media_id',
        'founded_year',
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
            'founded_year' => 'integer',
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

    public function parentCompany()
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    public function subsidiaries()
    {
        return $this->hasMany(Company::class, 'parent_company_id');
    }

    public function logoMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'logo_media_id');
    }

    public function coverMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'cover_media_id');
    }

    public function seoImageMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'seo_image_media_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'company_service');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function jobListings()
    {
        return $this->hasMany(JobListing::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
