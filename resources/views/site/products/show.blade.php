<x-layouts.public
    :seo-title="$product->seoTitle()"
    :seo-description="$product->seoDescription()"
    :seo-image="$product->seoImage()?->url()"
    :no-index="$product->no_index"
    :structured-data="[
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => $product->short_description,
    ]"
>
    <section class="border-b border-surface-border bg-white">
        <div class="container-site flex flex-col items-start gap-4 py-12 sm:py-16">
            <p class="text-eyebrow">Products &amp; platforms</p>
            @if ($product->logoMedia)
                <img src="{{ $product->logoMedia->url() }}" alt="{{ $product->name }}" class="h-12 w-auto">
            @endif
            <h1 class="font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">{{ $product->name }}</h1>
            @if ($product->tagline)
                <p class="max-w-2xl text-lg text-ink-muted">{{ $product->tagline }}</p>
            @endif
            @if ($product->website_url)
                <a href="{{ $product->website_url }}" target="_blank" rel="noopener noreferrer" class="btn-secondary mt-2">Visit {{ $product->name }} &rarr;</a>
            @endif
        </div>
    </section>

    <section class="section-spacing">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($product->screenshotMedia)
                    <img src="{{ $product->screenshotMedia->url() }}" alt="{{ $product->name }} screenshot" class="mb-8 w-full rounded-xl shadow-level2">
                @endif
                @if ($product->description)
                    <div class="prose prose-slate max-w-none">{!! $product->description !!}</div>
                @endif
            </div>
            <aside class="space-y-6">
                @if (! empty($product->features))
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Key Features</p>
                        <ul class="mt-2 space-y-2">
                            @foreach ($product->features as $feature)
                                <li class="flex items-start gap-2 text-sm text-ink">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-emerald" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 111.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($product->company)
                    <div class="card p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Operated By</p>
                        <a href="{{ route('companies.show', $product->company) }}" class="mt-1 block font-heading text-sm font-semibold text-brand-blue hover:underline">{{ $product->company->name }}</a>
                    </div>
                @endif
                <a href="{{ route('contact') }}" class="btn-primary block w-full text-center">Enquire About {{ $product->name }}</a>
            </aside>
        </div>
    </section>
</x-layouts.public>
