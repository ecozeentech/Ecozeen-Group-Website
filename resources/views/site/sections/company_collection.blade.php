@php
    $companies = \App\Models\Company::query()->published()
        ->when($settings['featured_only'] ?? false, fn ($q) => $q->featured())
        ->orderBy('sort_order')
        ->limit($settings['limit'] ?? 6)
        ->get();
@endphp
@if ($companies->isNotEmpty())
    <section class="section-spacing">
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
                    <a href="{{ $settings['cta_url'] ?: route('companies.index') }}" class="btn-outline shrink-0">{{ $settings['cta_label'] }}</a>
                @endif
            </div>
            <div class="mt-8 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($companies as $company)
                    <a href="{{ route('companies.show', $company) }}" class="card-accent flex flex-col items-center gap-3 p-6 text-center">
                        @if ($company->logoMedia)
                            <img src="{{ $company->logoMedia->url() }}" alt="{{ $company->name }}" class="h-12 w-auto">
                        @else
                            <span class="font-heading text-sm font-semibold text-ink">{{ $company->name }}</span>
                        @endif
                        <span class="text-xs text-ink-muted">{{ $company->region }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
