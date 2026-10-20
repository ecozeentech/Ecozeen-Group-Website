@php
    $items = array_filter($settings['items'] ?? [], 'is_array');
@endphp
@if (! empty($items))
    <section class="section-spacing">
        <div class="container-site max-w-3xl">
            @if (! empty($settings['heading']))
                <h2 class="text-center font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
            @endif
            <div class="mt-8 space-y-3">
                @foreach ($items as $item)
                    <div class="card p-5" x-data="{ open: false }">
                        <button type="button" class="flex w-full items-center justify-between text-left" @click="open = !open" :aria-expanded="open">
                            <span class="font-heading text-sm font-semibold text-ink">{{ $item['question'] ?? '' }}</span>
                            <svg class="h-5 w-5 shrink-0 text-ink-muted transition" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" /></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="mt-3 text-sm text-ink-muted">{{ $item['answer'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
