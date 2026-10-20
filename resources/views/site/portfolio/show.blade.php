<x-layouts.public
    :seo-title="$project->seoTitle()"
    :seo-description="$project->seoDescription()"
    :seo-image="$project->seoImage()?->url()"
    :no-index="$project->no_index"
>
    <section class="bg-brand-navy py-14 text-white">
        <div class="container-site text-center">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-300">{{ $project->category?->name }}</p>
            <h1 class="mt-2 font-heading text-3xl font-bold sm:text-4xl">{{ $project->title }}</h1>
            @if ($project->client)
                <p class="mt-2 text-sm text-slate-300">Client: {{ $project->client }}</p>
            @endif
        </div>
    </section>

    <section class="section-spacing">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($project->coverMedia)
                    <img src="{{ $project->coverMedia->url() }}" alt="{{ $project->title }}" class="mb-8 w-full rounded-xl shadow-level2">
                @endif
                @if ($project->description)
                    <div class="prose prose-slate max-w-none">{!! $project->description !!}</div>
                @endif
                @if ($project->outcomes)
                    <div class="mt-8">
                        <h2 class="font-heading text-xl font-bold text-ink">Outcomes</h2>
                        <div class="prose prose-slate mt-3 max-w-none">{!! $project->outcomes !!}</div>
                    </div>
                @endif
                @if ($project->galleryMedia->isNotEmpty())
                    <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($project->galleryMedia as $media)
                            <img src="{{ $media->url() }}" alt="{{ $media->alt_text }}" class="h-32 w-full rounded-lg object-cover">
                        @endforeach
                    </div>
                @endif
            </div>
            <aside class="space-y-6">
                @if ($project->company)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Delivered By</p>
                        <a href="{{ route('companies.show', $project->company) }}" class="mt-1 block font-heading text-sm font-semibold text-brand-blue hover:underline">{{ $project->company->name }}</a>
                    </div>
                @endif
                @if ($project->completed_at)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Completed</p>
                        <p class="mt-1 text-sm text-ink">{{ $project->completed_at->format('F Y') }}</p>
                    </div>
                @endif
                <a href="{{ route('contact') }}" class="btn-primary block w-full text-center">Start a Similar Project</a>
            </aside>
        </div>
    </section>
</x-layouts.public>
