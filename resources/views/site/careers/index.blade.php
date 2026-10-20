<x-layouts.public seo-title="Careers" seo-description="Open roles across Ecozeen Group companies.">
    <x-site.page-hero
        eyebrow="Join the group"
        title="Careers at Ecozeen Group"
        description="Open roles across Ecozeen Group companies and business divisions."
    />

    <section class="section-spacing">
        <div class="container-site">
            @if ($listings->isEmpty())
                <div class="card p-10 text-center">
                    <p class="font-heading text-lg font-semibold text-ink">No open roles right now</p>
                    <p class="mt-2 text-sm text-ink-muted">We are not actively hiring at the moment. Check back soon, or reach out via our contact page.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($listings as $listing)
                        <a href="{{ route('careers.show', $listing) }}" class="card-hover flex flex-col gap-2 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="font-heading text-base font-semibold text-ink">{{ $listing->title }}</h2>
                                <p class="mt-1 text-sm text-ink-muted">
                                    {{ $listing->company?->name }} @if($listing->department) &middot; {{ $listing->department }} @endif @if($listing->location) &middot; {{ $listing->location }} @endif
                                </p>
                            </div>
                            <span class="badge badge-info shrink-0">{{ \Illuminate\Support\Str::headline($listing->employment_type) }}</span>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $listings->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
