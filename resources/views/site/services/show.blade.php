<x-layouts.public
    :seo-title="$service->seoTitle()"
    :seo-description="$service->seoDescription()"
    :seo-image="$service->seoImage()?->url()"
    :no-index="$service->no_index"
    :structured-data="[
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->name,
        'description' => $service->summary,
    ]"
>
    <section class="bg-brand-navy py-14 text-white">
        <div class="container-site text-center">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-300">{{ $service->category?->name }}</p>
            <h1 class="mt-2 font-heading text-3xl font-bold sm:text-4xl">{{ $service->name }}</h1>
            @if ($service->summary)
                <p class="mx-auto mt-3 max-w-xl text-slate-200">{{ $service->summary }}</p>
            @endif
        </div>
    </section>

    <section class="section-spacing">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($service->description)
                    <div class="prose prose-slate max-w-none">{!! $service->description !!}</div>
                @endif

                @if ($service->projects->isNotEmpty())
                    <div class="mt-10">
                        <h2 class="font-heading text-xl font-bold text-ink">Related Portfolio Projects</h2>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach ($service->projects as $project)
                                <a href="{{ route('portfolio.show', $project) }}" class="card p-4 transition hover:shadow-level2">
                                    <p class="font-heading text-sm font-semibold text-ink">{{ $project->title }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <aside class="space-y-6">
                @if ($service->companies->isNotEmpty())
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Delivered By</p>
                        <ul class="mt-2 space-y-1">
                            @foreach ($service->companies as $company)
                                <li><a href="{{ route('companies.show', $company) }}" class="text-sm text-brand-blue hover:underline">{{ $company->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <a href="{{ route('contact') }}" class="btn-primary block w-full text-center">Discuss This Service</a>
            </aside>
        </div>
    </section>
</x-layouts.public>
