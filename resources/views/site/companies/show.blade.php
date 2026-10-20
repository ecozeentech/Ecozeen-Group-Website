@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $company->name,
        'url' => route('companies.show', $company),
    ];
    if ($company->website_url) {
        $structuredData['sameAs'] = [$company->website_url];
    }
@endphp
<x-layouts.public
    :seo-title="$company->seoTitle()"
    :seo-description="$company->seoDescription()"
    :seo-image="$company->seoImage()?->url()"
    :no-index="$company->no_index"
    :structured-data="$structuredData"
>
    <section class="bg-brand-navy py-12 text-white">
        <div class="container-site flex flex-col items-center gap-4 text-center">
            @if ($company->logoMedia)
                <img src="{{ $company->logoMedia->url() }}" alt="{{ $company->name }}" class="h-14 w-auto">
            @endif
            <h1 class="font-heading text-3xl font-bold sm:text-4xl">{{ $company->name }}</h1>
            <p class="text-sm font-medium uppercase tracking-wide text-slate-300">{{ \Illuminate\Support\Str::headline($company->type) }} &middot; {{ $company->region }}</p>
            @if ($company->short_description)
                <p class="max-w-2xl text-slate-200">{{ $company->short_description }}</p>
            @endif
            @if ($company->website_url)
                <a href="{{ $company->website_url }}" target="_blank" rel="noopener noreferrer" class="btn-secondary mt-2">Visit {{ $company->name }} &rarr;</a>
            @endif
        </div>
    </section>

    <section class="section-spacing">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($company->description)
                    <div class="prose prose-slate max-w-none">{!! $company->description !!}</div>
                @endif

                @if ($company->subsidiaries->isNotEmpty())
                    <div class="mt-10">
                        <h2 class="font-heading text-xl font-bold text-ink">Subsidiaries &amp; Divisions</h2>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach ($company->subsidiaries as $sub)
                                <a href="{{ route('companies.show', $sub) }}" class="card p-4 transition hover:shadow-level2">
                                    <p class="font-heading text-sm font-semibold text-ink">{{ $sub->name }}</p>
                                    <p class="text-xs text-ink-muted">{{ $sub->region }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($company->products->isNotEmpty())
                    <div class="mt-10">
                        <h2 class="font-heading text-xl font-bold text-ink">Products &amp; Platforms</h2>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            @foreach ($company->products as $product)
                                <a href="{{ route('products.show', $product) }}" class="card p-4 transition hover:shadow-level2">
                                    <p class="font-heading text-sm font-semibold text-ink">{{ $product->name }}</p>
                                    <p class="text-xs text-ink-muted">{{ $product->tagline }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                @if ($company->founded_year)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Founded</p>
                        <p class="mt-1 font-heading text-lg font-bold text-ink">{{ $company->founded_year }}</p>
                    </div>
                @endif
                @if ($company->services->isNotEmpty())
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Services</p>
                        <ul class="mt-2 space-y-1">
                            @foreach ($company->services as $service)
                                <li><a href="{{ route('services.show', $service) }}" class="text-sm text-brand-blue hover:underline">{{ $service->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <a href="{{ route('contact') }}" class="btn-primary block w-full text-center">Contact This Company</a>
            </aside>
        </div>
    </section>
</x-layouts.public>
