@php
    $assets = \App\Models\MediaAsset::whereIn('id', $settings['media_ids'] ?? [])->get();
@endphp
@if ($assets->isNotEmpty())
    <section class="section-spacing">
        <div class="container-site">
            @if (! empty($settings['heading']))
                <p class="text-center text-sm font-semibold uppercase tracking-wide text-ink-muted">{{ $settings['heading'] }}</p>
            @endif
            <div class="mt-6 flex flex-wrap items-center justify-center gap-10">
                @foreach ($assets as $asset)
                    <img src="{{ $asset->url() }}" alt="{{ $asset->alt_text }}" class="h-10 w-auto opacity-80 grayscale" loading="lazy">
                @endforeach
            </div>
        </div>
    </section>
@endif
