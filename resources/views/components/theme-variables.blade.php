@php
    $theme = \App\Models\ThemeSetting::current();

    $containerWidths = ['narrow' => '1024px', 'default' => '1280px', 'wide' => '1440px'];
    $spacingScale = ['compact' => '3.5rem', 'default' => '6rem', 'spacious' => '8rem'];
    $radiusScale = ['sharp' => '0.125rem', 'soft' => '0.5rem', 'rounded' => '1rem'];
@endphp
<style>
    :root {
        --color-primary: {{ $theme->primary_color }};
        --color-secondary: {{ $theme->secondary_color }};
        --color-accent: {{ $theme->accent_color }};
        --color-success: {{ $theme->success_color }};
        --color-text: {{ $theme->text_color }};
        --color-text-muted: {{ $theme->muted_text_color }};
        --color-background: {{ $theme->background_color }};
        --color-surface: {{ $theme->surface_color }};
        --color-border: {{ $theme->border_color }};
        --font-heading: '{{ $theme->heading_font }}', sans-serif;
        --font-body: '{{ $theme->body_font }}', sans-serif;
        --container-width: {{ $containerWidths[$theme->container_width] ?? '1280px' }};
        --section-spacing: {{ $spacingScale[$theme->section_spacing] ?? '6rem' }};
        --radius-base: {{ $radiusScale[$theme->radius_scale] ?? '0.5rem' }};
    }
</style>
