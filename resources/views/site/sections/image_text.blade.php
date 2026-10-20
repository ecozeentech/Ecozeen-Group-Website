@php
    $image = ! empty($settings['image_media_id']) ? \App\Models\MediaAsset::find($settings['image_media_id']) : null;
    $reverse = ($settings['image_position'] ?? 'right') === 'left';
@endphp
<section class="section-spacing">
    <div class="container-site flex flex-col items-center gap-10 lg:flex-row {{ $reverse ? 'lg:flex-row-reverse' : '' }}">
        <div class="flex-1">
            @if (! empty($settings['heading']))
                <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            @if (! empty($settings['content']))
                <div class="prose prose-slate mt-4 max-w-none">{!! $settings['content'] !!}</div>
            @endif
            @if (! empty($settings['cta_label']))
                <a href="{{ $settings['cta_url'] ?: '#' }}" class="btn-primary mt-5">{{ $settings['cta_label'] }}</a>
            @endif
        </div>
        @if ($image)
            <div class="flex-1">
                <img src="{{ $image->url() }}" alt="{{ $image->alt_text }}" class="w-full rounded-xl shadow-level2" loading="lazy">
            </div>
        @endif
    </div>
</section>
