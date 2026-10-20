<x-layouts.public seo-title="Our Companies" seo-description="Explore the companies and business divisions across Ecozeen Group.">
    <x-site.page-hero
        eyebrow="Subsidiary directory"
        title="Companies &amp; Business Units"
        description="Ecozeen Group operates a portfolio of companies and business divisions spanning financial technology, digital marketplaces, technology services, mining, and agriculture."
    >
        <form method="GET" class="mt-6 flex items-center gap-3">
            <select name="region" class="form-select max-w-xs" onchange="this.form.submit()">
                <option value="">All regions</option>
                @foreach ($regions as $region)
                    <option value="{{ $region }}" @selected(request('region') === $region)>{{ $region }}</option>
                @endforeach
            </select>
        </form>
    </x-site.page-hero>

    <section class="section-spacing">
        <div class="container-site">
            @if ($companies->isEmpty())
                <p class="text-ink-muted">No companies are published yet. Check back soon.</p>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        <a href="{{ route('companies.show', $company) }}" class="card-accent block p-6">
                            @if ($company->logoMedia)
                                <img src="{{ $company->logoMedia->url() }}" alt="{{ $company->name }}" class="h-10 w-auto">
                            @endif
                            <h2 class="mt-4 font-heading text-lg font-semibold text-ink">{{ $company->name }}</h2>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-brand-blue">{{ \Illuminate\Support\Str::headline($company->type) }} @if($company->region)&middot; {{ $company->region }}@endif</p>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($company->short_description, 110) }}</p>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $companies->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
