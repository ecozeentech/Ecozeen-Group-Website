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

class BlogPost extends Model
{
    use HasFactory, HasPublishingStatus, HasSeoFields, HasSlug, SoftDeletes;

    protected $fillable = [
        'blog_category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_media_id',
        'status',
        'published_at',
        'scheduled_at',
        'is_featured',
        'reading_minutes',
        'seo_title',
        'seo_description',
        'seo_image_media_id',
        'no_index',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'is_featured' => 'boolean',
            'no_index' => 'boolean',
            'body' => PurifyHtmlOnSet::class,
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

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
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

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post) {
            if (empty($post->reading_minutes) && ! empty($post->body)) {
                $words = str_word_count(strip_tags($post->body));
                $post->reading_minutes = max(1, (int) ceil($words / 200));
            }
        });
    }
}
