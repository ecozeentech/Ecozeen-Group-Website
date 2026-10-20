<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
    protected $fillable = [
        'primary_color',
        'secondary_color',
        'accent_color',
        'success_color',
        'text_color',
        'muted_text_color',
        'background_color',
        'surface_color',
        'border_color',
        'heading_font',
        'body_font',
        'container_width',
        'section_spacing',
        'radius_scale',
        'shadow_scale',
        'header_variant',
        'footer_variant',
        'button_style',
        'card_style',
        'dark_mode_enabled',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'dark_mode_enabled' => 'boolean',
        ];
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
        $attributes = Cache::rememberForever('theme_settings.current', function () {
            return self::query()->firstOrCreate(['id' => 1])->getAttributes();
        });

        return (new self)->newFromBuilder($attributes);
    }

    /**
     * Allowed, validated option sets for select-based theme fields. Keeping
     * these centrally defined prevents administrators from persisting
     * arbitrary/unsafe values through mass assignment.
     */
    public static function allowedOptions(): array
    {
        return [
            'heading_font' => ['Manrope', 'Inter', 'Poppins', 'Sora'],
            'body_font' => ['Inter', 'Manrope', 'Source Sans Pro'],
            'container_width' => ['narrow', 'default', 'wide'],
            'section_spacing' => ['compact', 'default', 'spacious'],
            'radius_scale' => ['sharp', 'soft', 'rounded'],
            'shadow_scale' => ['flat', 'subtle', 'elevated'],
            'header_variant' => ['standard', 'glass', 'transparent'],
            'footer_variant' => ['standard', 'minimal', 'expanded'],
            'button_style' => ['solid', 'outline', 'pill'],
            'card_style' => ['bordered', 'shadowed', 'flat'],
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('theme_settings.current'));
        static::deleted(fn () => Cache::forget('theme_settings.current'));
    }
}
