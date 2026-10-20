@php
    $src = match ($settings['provider'] ?? null) {
        'youtube' => 'https://www.youtube.com/embed/'.($settings['video_id'] ?? ''),
        'vimeo' => 'https://player.vimeo.com/video/'.($settings['video_id'] ?? ''),
        default => null,
    };
@endphp
@if ($src)
    <section class="section-spacing">
        <div class="container-site max-w-4xl">
            @if (! empty($settings['heading']))
                <h2 class="mb-6 text-center font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            <div class="aspect-video overflow-hidden rounded-xl shadow-level2">
                <iframe src="{{ $src }}" class="h-full w-full" title="{{ $settings['heading'] ?? 'Video' }}" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
            @if (! empty($settings['caption']))
                <p class="mt-3 text-center text-sm text-ink-muted">{{ $settings['caption'] }}</p>
            @endif
        </div>
    </section>
@endif
