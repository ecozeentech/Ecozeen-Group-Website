<x-layouts.public seo-title="Services" seo-description="Explore the services offered across Ecozeen Group companies.">
    <section class="section-spacing">
        <div class="container-site">
            <div class="max-w-2xl">
                <h1 class="font-heading text-3xl font-bold text-ink sm:text-4xl">Our Services</h1>
                <p class="mt-3 text-ink-muted">A unified range of institutional, technology, and commodity services delivered by Ecozeen Group companies.</p>
            </div>

            @if ($services->isEmpty())
                <p class="mt-10 text-ink-muted">No services are published yet. Check back soon.</p>
            @else
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <a href="{{ route('services.show', $service) }}" class="card block p-6 transition hover:shadow-level2">
                            <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $service->category?->name }}</p>
                            <h2 class="mt-1 font-heading text-lg font-semibold text-ink">{{ $service->name }}</h2>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($service->summary, 110) }}</p>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $services->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
