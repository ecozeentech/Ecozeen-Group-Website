@php
    $items = array_filter($settings['items'] ?? [], 'is_array');
@endphp
@if (! empty($items))
    <section class="section-spacing bg-surface-canvas">
        <div class="container-site">
            @if (! empty($settings['heading']))
                <h2 class="text-center font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
                @foreach ($items as $item)
                    <blockquote class="card p-6">
                        <p class="text-sm italic text-ink">&ldquo;{{ $item['quote'] ?? '' }}&rdquo;</p>
                        <footer class="mt-4 text-xs font-semibold text-ink-muted">
                            {{ $item['name'] ?? '' }}@if (! empty($item['role'])), {{ $item['role'] }}@endif
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        </div>
    </section>
@endif
