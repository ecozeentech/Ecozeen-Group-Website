<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'supporting_statement',
        'logo_media_id',
        'logo_dark_media_id',
        'favicon_media_id',
        'default_og_image_media_id',
        'contact_email',
        'contact_phone',
        'inquiry_email',
        'press_email',
        'address_primary',
        'address_secondary',
        'social_links',
        'announcement_text',
        'announcement_enabled',
        'footer_text',
        'footer_legal_text',
        'google_analytics_id',
        'maintenance_mode',
        'maintenance_message',
        'newsletter_enabled',
        'sitemap_enabled',
        'search_indexing_enabled',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
            'announcement_enabled' => 'boolean',
            'maintenance_mode' => 'boolean',
            'newsletter_enabled' => 'boolean',
            'sitemap_enabled' => 'boolean',
            'search_indexing_enabled' => 'boolean',
        ];
    }

    public function logoMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'logo_media_id');
    }

    public function logoDarkMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'logo_dark_media_id');
    }

    public function faviconMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'favicon_media_id');
    }

    public function defaultOgImageMedia()
    {
        return $this->belongsTo(MediaAsset::class, 'default_og_image_media_id');
    }

    /**
     * Cache plain attributes rather than the hydrated model: the default
     * cache.serializable_classes config disallows unserializing any PHP
     * object (to prevent gadget-chain deserialization attacks), so a
     * cached Eloquent model would silently come back as
     * __PHP_Incomplete_Class. newFromBuilder() re-hydrates a real model
     * from the cached attribute array.
     */
    public static function current(): self
    {
        $attributes = Cache::rememberForever('website_settings.current', function () {
            return self::query()->firstOrCreate(['id' => 1])->getAttributes();
        });

        return (new self)->newFromBuilder($attributes);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('website_settings.current'));
        static::deleted(fn () => Cache::forget('website_settings.current'));
    }
}
