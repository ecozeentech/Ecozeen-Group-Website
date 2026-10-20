@php
    $image = ! empty($settings['image_media_id']) ? \App\Models\MediaAsset::find($settings['image_media_id']) : null;
@endphp
@if ($image)
    <figure>
        <img src="{{ $image->url() }}" alt="{{ $settings['alt_override'] ?: $image->alt_text }}" class="w-full object-cover" loading="lazy">
        @if (! empty($settings['caption']))
            <figcaption class="container-site py-3 text-center text-sm text-ink-muted">{{ $settings['caption'] }}</figcaption>
        @endif
    </figure>
@endif
