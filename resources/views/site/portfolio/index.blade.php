<x-layouts.public seo-title="Portfolio" seo-description="A selection of projects delivered across Ecozeen Group.">
    <x-site.page-hero
        eyebrow="Selected work"
        title="Portfolio"
        description="Selected projects delivered across Ecozeen Group companies."
    />

    <section class="section-spacing">
        <div class="container-site">
            @if ($projects->isEmpty())
                <p class="text-ink-muted">No projects are published yet. Check back soon.</p>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <a href="{{ route('portfolio.show', $project) }}" class="card-hover overflow-hidden">
                            @if ($project->coverMedia)
                                <img src="{{ $project->coverMedia->url() }}" alt="{{ $project->title }}" class="h-44 w-full object-cover">
                            @endif
                            <div class="p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $project->category?->name }}</p>
                                <h2 class="mt-1 font-heading text-base font-semibold text-ink">{{ $project->title }}</h2>
                                <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($project->summary, 100) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $projects->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
