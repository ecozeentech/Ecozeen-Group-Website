@php
    $image = ! empty($settings['image_media_id']) ? \App\Models\MediaAsset::find($settings['image_media_id']) : null;
    $alignment = ($settings['alignment'] ?? 'left') === 'center' ? 'text-center items-center' : 'text-left items-start';
@endphp
<section class="relative overflow-hidden bg-brand-navy text-white">
    <div class="container-site section-spacing flex flex-col gap-10 lg:flex-row lg:items-center">
        <div class="flex flex-1 flex-col {{ $alignment }} gap-5">
            @if (! empty($settings['eyebrow']))
                <span class="badge bg-white/10 text-white ring-1 ring-inset ring-white/20">{{ $settings['eyebrow'] }}</span>
            @endif
            @if (! empty($settings['heading']))
                <h1 class="font-heading text-4xl font-extrabold leading-tight sm:text-5xl">{{ $settings['heading'] }}</h1>
            @endif
            @if (! empty($settings['subheading']))
                <div class="max-w-xl text-lg text-slate-200">{!! $settings['subheading'] !!}</div>
            @endif
            <div class="mt-2 flex flex-wrap gap-3">
                @if (! empty($settings['primary_label']))
                    <a href="{{ $settings['primary_url'] ?: '#' }}" class="btn-secondary">{{ $settings['primary_label'] }}</a>
                @endif
                @if (! empty($settings['secondary_label']))
                    <a href="{{ $settings['secondary_url'] ?: '#' }}" class="btn-outline border-white/30 bg-transparent text-white hover:bg-white/10">{{ $settings['secondary_label'] }}</a>
                @endif
            </div>
        </div>
        @if ($image)
            <div class="flex-1">
                <img src="{{ $image->url() }}" alt="{{ $image->alt_text }}" class="w-full rounded-2xl shadow-level3" loading="eager">
            </div>
        @endif
    </div>
</section>
