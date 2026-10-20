@php
    $services = \App\Models\Service::query()->published()
        ->when($settings['featured_only'] ?? false, fn ($q) => $q->featured())
        ->orderBy('sort_order')
        ->limit($settings['limit'] ?? 6)
        ->get();
@endphp
@if ($services->isNotEmpty())
    <section class="section-spacing bg-surface-canvas">
        <div class="container-site">
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    @if (! empty($settings['heading']))
                        <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
                    @endif
                    @if (! empty($settings['subheading']))
                        <p class="mt-2 text-ink-muted">{{ $settings['subheading'] }}</p>
                    @endif
                </div>
                @if (! empty($settings['cta_label']))
                    <a href="{{ $settings['cta_url'] ?: route('services.index') }}" class="btn-outline shrink-0">{{ $settings['cta_label'] }}</a>
                @endif
            </div>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <a href="{{ route('services.show', $service) }}" class="card block p-6 transition hover:shadow-level2">
                        <h3 class="font-heading text-base font-semibold text-ink">{{ $service->name }}</h3>
                        <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($service->summary, 110) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
