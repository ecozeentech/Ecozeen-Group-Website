@php
    $items = array_filter($settings['items'] ?? [], 'is_array');
@endphp
@if (! empty($items))
    <section class="section-spacing bg-brand-navy text-white">
        <div class="container-site">
            @if (! empty($settings['heading']))
                <h2 class="text-center font-heading text-2xl font-bold sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            <div class="mt-10 grid grid-cols-2 gap-8 text-center sm:grid-cols-4">
                @foreach ($items as $item)
                    <div>
                        <p class="font-heading text-3xl font-extrabold sm:text-4xl">{{ $item['value'] ?? '' }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $item['label'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
