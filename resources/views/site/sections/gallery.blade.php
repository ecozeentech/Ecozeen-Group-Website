@php
    $assets = \App\Models\MediaAsset::whereIn('id', $settings['media_ids'] ?? [])->get()->sortBy(fn ($a) => array_search($a->id, $settings['media_ids'] ?? []));
    $columnClasses = [1 => 'sm:grid-cols-1', 2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-3', 4 => 'sm:grid-cols-4'];
    $columnClass = $columnClasses[max(1, min((int) ($settings['columns'] ?? 3), 4))];
@endphp
@if ($assets->isNotEmpty())
    <section class="section-spacing">
        <div class="container-site">
            @if (! empty($settings['heading']))
                <h2 class="mb-8 text-center font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            <div class="grid grid-cols-2 gap-4 {{ $columnClass }}">
                @foreach ($assets as $asset)
                    <img src="{{ $asset->url() }}" alt="{{ $asset->alt_text }}" class="h-48 w-full rounded-xl object-cover shadow-level1" loading="lazy">
                @endforeach
            </div>
        </div>
    </section>
@endif
