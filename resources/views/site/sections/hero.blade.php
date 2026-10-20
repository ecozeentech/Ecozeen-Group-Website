@php
    $image = ! empty($settings['image_media_id']) ? \App\Models\MediaAsset::find($settings['image_media_id']) : null;
    $alignment = ($settings['alignment'] ?? 'left') === 'center' ? 'text-center items-center' : 'text-left items-start';
    $style = $settings['background_style'] ?? 'light';
    $isNavy = in_array($style, ['navy', 'dark', 'primary'], true);
@endphp
<section class="relative overflow-hidden {{ $isNavy ? 'bg-brand-navy text-white' : 'border-b border-surface-border bg-white text-ink' }}">
    <div class="container-site section-spacing grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
        <div class="flex flex-col {{ $alignment }} gap-6 {{ $image ? 'lg:col-span-7' : 'lg:col-span-12' }}">
            @if (! empty($settings['eyebrow']))
                <span class="text-eyebrow {{ $isNavy ? 'text-emerald-300' : '' }}">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-emerald" aria-hidden="true"></span>
                    {{ $settings['eyebrow'] }}
                </span>
            @endif
            @if (! empty($settings['heading']))
                <h1 class="display-xl {{ $isNavy ? 'text-white' : '' }}">{!! nl2br(e($settings['heading'])) !!}</h1>
            @endif
            @if (! empty($settings['subheading']))
                <div class="max-w-2xl text-lg leading-relaxed {{ $isNavy ? 'text-slate-200' : 'text-ink-muted' }}">{!! $settings['subheading'] !!}</div>
            @endif
            <div class="mt-2 flex flex-wrap gap-3">
                @if (! empty($settings['primary_label']))
                    <a href="{{ $settings['primary_url'] ?: '#' }}" class="{{ $isNavy ? 'btn-secondary' : 'btn-primary' }}">
                        {{ $settings['primary_label'] }}
                    </a>
                @endif
                @if (! empty($settings['secondary_label']))
                    <a href="{{ $settings['secondary_url'] ?: '#' }}" class="{{ $isNavy ? 'btn-outline border-white/30 bg-transparent text-white hover:bg-white/10' : 'btn-outline' }}">
                        {{ $settings['secondary_label'] }}
                    </a>
                @endif
            </div>
        </div>
        @if ($image)
            <div class="lg:col-span-5">
                <div class="overflow-hidden rounded-xl border border-surface-border bg-white shadow-level1">
                    <img src="{{ $image->url() }}" alt="{{ $image->alt_text }}" class="w-full object-cover" loading="eager">
                </div>
            </div>
        @endif
    </div>
</section>
