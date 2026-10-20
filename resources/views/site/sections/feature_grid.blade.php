@php
    $items = array_filter($settings['items'] ?? [], 'is_array');
    $columnClasses = [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4'];
    $columnClass = $columnClasses[max(1, min((int) ($settings['columns'] ?? 3), 4))];
@endphp
@if (! empty($items))
    <section class="section-spacing">
        <div class="container-site">
            <div class="mx-auto max-w-2xl text-center">
                @if (! empty($settings['heading']))
                    <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
                @endif
                @if (! empty($settings['subheading']))
                    <p class="mt-3 text-ink-muted">{{ $settings['subheading'] }}</p>
                @endif
            </div>
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 {{ $columnClass }}">
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
