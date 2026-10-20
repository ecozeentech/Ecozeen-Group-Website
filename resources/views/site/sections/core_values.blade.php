@php
    $items = array_filter($settings['items'] ?? [], 'is_array');
@endphp
@if (! empty($items))
    <section class="section-spacing bg-surface-canvas">
        <div class="container-site">
            @if (! empty($settings['heading']))
                <h2 class="text-center font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($items as $item)
                    <div class="card p-6">
                        <h3 class="font-heading text-base font-semibold text-ink">{{ $item['title'] ?? '' }}</h3>
                        <p class="mt-2 text-sm text-ink-muted">{{ $item['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
