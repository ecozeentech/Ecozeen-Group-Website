@php
    $projects = \App\Models\Project::query()->published()
        ->when($settings['featured_only'] ?? false, fn ($q) => $q->featured())
        ->orderBy('sort_order')
        ->limit($settings['limit'] ?? 6)
        ->get();
@endphp
@if ($projects->isNotEmpty())
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
                    <a href="{{ $settings['cta_url'] ?: route('portfolio.index') }}" class="btn-outline shrink-0">{{ $settings['cta_label'] }}</a>
                @endif
            </div>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <a href="{{ route('portfolio.show', $project) }}" class="card overflow-hidden transition hover:shadow-level2">
                        @if ($project->coverMedia)
                            <img src="{{ $project->coverMedia->url() }}" alt="{{ $project->title }}" class="h-40 w-full object-cover">
                        @endif
                        <div class="p-5">
                            <h3 class="font-heading text-base font-semibold text-ink">{{ $project->title }}</h3>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($project->summary, 100) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
