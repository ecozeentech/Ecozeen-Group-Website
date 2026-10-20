<?php

namespace App\Support\PageBuilder;

use Stevebauman\Purify\Facades\Purify;

/**
 * Authoritative, server-side registry of every section type the page
 * builder supports, along with the shape of its settings. The browser may
 * submit arbitrary JSON for a section's settings, but it is never trusted:
 * sanitize() strips any key that isn't declared for the given type and
 * purifies rich-text fields through the HTML allowlist sanitizer before
 * anything is persisted or rendered on the public site.
 */
class SectionTypeRegistry
{
    /**
     * type => [
     *   'label' => human label shown in the admin page builder,
     *   'fields' => [ fieldName => 'string'|'richtext'|'bool'|'int'|'url'|'list'|'collection' ],
     * ]
     */
    public static function definitions(): array
    {
        return [
            'hero' => [
                'label' => 'Hero',
                'fields' => [
                    'eyebrow' => 'string', 'heading' => 'string', 'subheading' => 'richtext',
                    'primary_label' => 'string', 'primary_url' => 'url',
                    'secondary_label' => 'string', 'secondary_url' => 'url',
                    'image_media_id' => 'int', 'background_style' => 'string', 'alignment' => 'string',
                ],
            ],
            'rich_text' => [
                'label' => 'Rich Text',
                'fields' => ['heading' => 'string', 'content' => 'richtext', 'alignment' => 'string', 'max_width' => 'string'],
            ],
            'image_text' => [
                'label' => 'Image & Text',
                'fields' => [
                    'heading' => 'string', 'content' => 'richtext', 'image_media_id' => 'int',
                    'image_position' => 'string', 'cta_label' => 'string', 'cta_url' => 'url',
                ],
            ],
            'full_width_image' => [
                'label' => 'Full-width Image',
                'fields' => ['image_media_id' => 'int', 'caption' => 'string', 'alt_override' => 'string'],
            ],
            'gallery' => [
                'label' => 'Image Gallery',
                'fields' => ['heading' => 'string', 'media_ids' => 'list', 'columns' => 'int'],
            ],
            'video_embed' => [
                'label' => 'Video Embed',
                'fields' => ['heading' => 'string', 'provider' => 'string', 'video_id' => 'string', 'caption' => 'string'],
            ],
            'feature_grid' => [
                'label' => 'Feature Grid',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'items' => 'list', 'columns' => 'int'],
            ],
            'product_collection' => [
                'label' => 'Product Collection',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'featured_only' => 'bool', 'limit' => 'int', 'cta_label' => 'string', 'cta_url' => 'url'],
            ],
            'company_collection' => [
                'label' => 'Company Collection',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'featured_only' => 'bool', 'limit' => 'int', 'cta_label' => 'string', 'cta_url' => 'url'],
            ],
            'service_collection' => [
                'label' => 'Service Collection',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'featured_only' => 'bool', 'limit' => 'int', 'cta_label' => 'string', 'cta_url' => 'url'],
            ],
            'portfolio_collection' => [
                'label' => 'Portfolio Collection',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'featured_only' => 'bool', 'limit' => 'int', 'cta_label' => 'string', 'cta_url' => 'url'],
            ],
            'blog_collection' => [
                'label' => 'Blog Collection',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'featured_only' => 'bool', 'limit' => 'int', 'cta_label' => 'string', 'cta_url' => 'url'],
            ],
            'faq' => [
                'label' => 'FAQ Accordion',
                'fields' => ['heading' => 'string', 'items' => 'list'],
            ],
            'stats' => [
                'label' => 'Statistics',
                'fields' => ['heading' => 'string', 'items' => 'list'],
            ],
            'mission_vision' => [
                'label' => 'Mission & Vision',
                'fields' => ['mission_heading' => 'string', 'mission_text' => 'richtext', 'vision_heading' => 'string', 'vision_text' => 'richtext'],
            ],
            'core_values' => [
                'label' => 'Core Values',
                'fields' => ['heading' => 'string', 'items' => 'list'],
            ],
            'partner_logos' => [
                'label' => 'Partner / Subsidiary Logos',
                'fields' => ['heading' => 'string', 'media_ids' => 'list'],
            ],
            'testimonials' => [
                'label' => 'Testimonials',
                'fields' => ['heading' => 'string', 'items' => 'list'],
            ],
            'cta' => [
                'label' => 'Call To Action',
                'fields' => ['heading' => 'string', 'subheading' => 'string', 'primary_label' => 'string', 'primary_url' => 'url', 'style' => 'string'],
            ],
            'newsletter_form' => [
                'label' => 'Newsletter Signup Form',
                'fields' => ['heading' => 'string', 'subheading' => 'string'],
            ],
            'contact_form' => [
                'label' => 'Contact Form',
                'fields' => ['heading' => 'string', 'subheading' => 'string'],
            ],
            'divider' => [
                'label' => 'Divider',
                'fields' => ['style' => 'string'],
            ],
            'spacer' => [
                'label' => 'Spacer',
                'fields' => ['height' => 'string'],
            ],
            'custom_content' => [
                'label' => 'Custom Content Block',
                'fields' => ['heading' => 'string', 'content' => 'richtext'],
            ],
        ];
    }

    public static function types(): array
    {
        return array_keys(self::definitions());
    }

    public static function label(string $type): string
    {
        return self::definitions()[$type]['label'] ?? $type;
    }

    public static function isValidType(string $type): bool
    {
        return array_key_exists($type, self::definitions());
    }

    /**
     * Strip any key not declared for this section type, coerce scalar
     * values to their expected type, and purify rich-text/html values.
     * Only validated, known providers are permitted for video embeds, and
     * only relative/http(s) URLs are permitted for link fields.
     */
    public static function sanitize(string $type, array $raw): array
    {
        $definition = self::definitions()[$type] ?? null;

        if (! $definition) {
            return [];
        }

        $clean = [];

        foreach ($definition['fields'] as $field => $fieldType) {
            if (! array_key_exists($field, $raw)) {
                continue;
            }

            $value = $raw[$field];

            $clean[$field] = match ($fieldType) {
                'string' => is_scalar($value) ? strip_tags((string) $value) : null,
                'richtext' => is_scalar($value) ? Purify::clean((string) $value) : null,
                'bool' => (bool) $value,
                'int' => is_numeric($value) ? (int) $value : null,
                'url' => self::sanitizeUrl($value),
                'list' => self::sanitizeList($value),
                'collection' => is_array($value) ? $value : [],
                default => null,
            };
        }

        if ($type === 'video_embed') {
            $clean['provider'] = in_array($clean['provider'] ?? null, ['youtube', 'vimeo'], true) ? $clean['provider'] : null;
            $clean['video_id'] = isset($clean['video_id']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $clean['video_id']) : null;
        }

        return $clean;
    }

    private static function sanitizeUrl(mixed $value): ?string
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        // Permit same-site relative paths or http(s) absolute URLs only.
        if (str_starts_with($value, '/')) {
            return strip_tags($value);
        }

        if (filter_var($value, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $value)) {
            return $value;
        }

        return null;
    }

    private static function sanitizeList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $sanitizeScalarsRecursively = function ($item) use (&$sanitizeScalarsRecursively) {
            if (is_array($item)) {
                return array_map($sanitizeScalarsRecursively, $item);
            }

            return is_scalar($item) ? strip_tags((string) $item) : null;
        };

        return array_map($sanitizeScalarsRecursively, $value);
    }
}
