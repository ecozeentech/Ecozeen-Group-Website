<x-layouts.public seo-title="Portfolio" seo-description="A selection of verified projects delivered across Ecozeen Group.">
    <section class="section-spacing">
        <div class="container-site">
            <div class="max-w-2xl">
                <h1 class="font-heading text-3xl font-bold text-ink sm:text-4xl">Portfolio</h1>
                <p class="mt-3 text-ink-muted">Selected projects delivered across Ecozeen Group companies.</p>
            </div>

            @if ($projects->isEmpty())
                <p class="mt-10 text-ink-muted">No projects are published yet. Check back soon.</p>
            @else
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <a href="{{ route('portfolio.show', $project) }}" class="card overflow-hidden transition hover:shadow-level2">
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
