<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stevebauman\Purify\Casts\PurifyHtmlOnSet;

class JobListing extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'description',
        'requirements',
        'status',
        'closes_at',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'closes_at' => 'date',
            'description' => PurifyHtmlOnSet::class,
            'requirements' => PurifyHtmlOnSet::class,
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

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('closes_at')->orWhere('closes_at', '>=', now()->toDateString());
            });
    }
}
