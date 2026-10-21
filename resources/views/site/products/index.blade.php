@php
    $items = $products->getCollection();
    $spotlight = ($products->onFirstPage() && ! request('category')) ? $items->first() : null;
    $grid = $spotlight ? $items->slice(1) : $items;
@endphp
<x-layouts.public seo-title="Products &amp; Platforms" seo-description="Explore the digital products and platforms built across Ecozeen Group.">
    <section class="relative overflow-hidden bg-gradient-to-b from-white via-slate-50 to-surface-canvas pt-16 pb-20 md:pt-24 md:pb-28">
        <div class="absolute inset-0 bg-[radial-gradient(#c6c6cd_1px,transparent_1px)] [background-size:24px_24px] opacity-40 pointer-events-none"></div>
        <div class="container-site relative text-center">
            <div class="mx-auto max-w-4xl space-y-6">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-blue/20 bg-blue-50 px-3 py-1">
                    <span class="h-2 w-2 rounded-full bg-brand-blue"></span>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-brand-blue">Proprietary digital infrastructure</span>
                </span>
                <h1 class="display-xl">Digital platforms powering trade, finance, and enterprise operations</h1>
                <p class="mx-auto max-w-2xl text-lg text-ink-muted">Discover software platforms incubated and operated by Ecozeen Group companies.</p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <a href="#platform-showcase" class="btn-secondary">
                        Explore platform suite
                        <x-icon name="south" class="text-[18px]" />
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline">
                        <x-icon name="verified_user" class="text-ink-muted" />
                        Talk to a platform team
                    </a>
                </div>
            </div>
            <div class="mt-12 grid grid-cols-2 gap-4 text-left md:grid-cols-4">
                <div class="card p-4">
                    <p class="text-[11px] uppercase tracking-wider text-ink-muted">Published platforms</p>
                    <p class="mt-1 font-heading text-2xl font-bold text-ink">{{ $products->total() }}</p>
                </div>
                <div class="card p-4">
                    <p class="text-[11px] uppercase tracking-wider text-ink-muted">Categories</p>
                    <p class="mt-1 font-heading text-2xl font-bold text-ink">{{ $categories->count() ?: '—' }}</p>
                </div>
                <div class="card p-4">
                    <p class="text-[11px] uppercase tracking-wider text-ink-muted">Featured</p>
                    <p class="mt-1 font-heading text-2xl font-bold text-ink">{{ $items->where('is_featured', true)->count() }}</p>
                </div>
                <div class="card p-4">
                    <p class="text-[11px] uppercase tracking-wider text-ink-muted">Status</p>
                    <p class="mt-1 font-heading text-2xl font-bold text-emerald-700">Live catalogue</p>
                </div>
            </div>
        </div>
    </section>

    @if ($categories->isNotEmpty())
        <section class="sticky top-20 z-40 border-y border-surface-border bg-white/95 py-3 backdrop-blur-md">
            <div class="container-site flex items-center justify-between gap-2 overflow-x-auto">
                <div class="flex items-center gap-2">
                    <a href="{{ route('products.index') }}" @class(['whitespace-nowrap rounded-lg px-4 py-2 text-sm font-semibold', 'bg-brand-navy text-white shadow-sm' => ! request('category'), 'text-ink-muted hover:bg-slate-50 hover:text-ink' => request('category')])>All platforms</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" @class(['whitespace-nowrap rounded-lg px-4 py-2 text-sm font-semibold', 'bg-brand-navy text-white shadow-sm' => request('category') === $category->slug, 'text-ink-muted hover:bg-slate-50 hover:text-ink' => request('category') !== $category->slug])>{{ $category->name }}</a>
                    @endforeach
                </div>
                <span class="hidden shrink-0 items-center gap-2 text-[11px] text-ink-muted lg:inline-flex">
                    <span class="h-2 w-2 rounded-full bg-brand-emerald"></span>
                    {{ $products->total() }} production listings
                </span>
            </div>
        </section>
    @endif

    <section id="platform-showcase" class="container-site space-y-16 py-20">
        @if ($products->isEmpty())
            <p class="text-ink-muted">No products are published yet. Check back soon.</p>
        @else
            @if ($spotlight)
                <article class="card-accent relative overflow-hidden p-6 md:p-10">
                    <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12">
                        <div class="space-y-6 lg:col-span-7">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="badge-info">{{ $spotlight->categories->pluck('name')->first() ?: 'Digital platform' }}</span>
                                @if ($spotlight->is_featured)
                                    <span class="badge-success">
                                        <span class="h-1.5 w-1.5 rounded-full bg-brand-emerald"></span>
                                        Featured
                                    </span>
                                @endif
                            </div>
                            @if ($spotlight->logoMedia)
                                <img src="{{ $spotlight->logoMedia->url() }}" alt="{{ $spotlight->name }}" class="h-12 w-auto">
                            @endif
                            <div>
                                <h2 class="font-heading text-3xl font-bold text-ink">{{ $spotlight->name }}</h2>
                                @if ($spotlight->tagline)
                                    <p class="mt-1 font-heading text-lg font-medium text-brand-blue">{{ $spotlight->tagline }}</p>
                                @endif
                                <p class="mt-3 text-ink-muted">{{ $spotlight->short_description }}</p>
                            </div>
                            @if (is_array($spotlight->features) && count($spotlight->features))
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    @foreach (array_slice($spotlight->features, 0, 4) as $feature)
                                        <div class="flex items-start gap-2 text-sm text-ink">
                                            <x-icon name="check_circle" class="mt-0.5 text-brand-blue text-[18px]" />
                                            <span>{{ is_array($feature) ? ($feature['title'] ?? $feature['label'] ?? reset($feature)) : $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('products.show', $spotlight) }}" class="btn-primary">View platform</a>
                                @if ($spotlight->website_url)
                                    <a href="{{ $spotlight->website_url }}" target="_blank" rel="noopener" class="btn-outline">
                                        Open product site
                                        <x-icon name="open_in_new" class="text-[16px]" />
                                    </a>
                                @endif
                            </div>
                            @if ($spotlight->company)
                                <p class="text-xs text-ink-muted">Operated by {{ $spotlight->company->name }}</p>
                            @endif
                        </div>
                        <div class="lg:col-span-5">
                            @if ($spotlight->screenshotMedia)
                                <img src="{{ $spotlight->screenshotMedia->url() }}" alt="{{ $spotlight->name }} screenshot" class="w-full rounded-xl border border-surface-border object-cover">
                            @else
                                <div class="flex h-64 items-center justify-center rounded-xl bg-slate-900">
                                    <x-brand.logo-mark class="h-16 w-16 brightness-0 invert" />
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endif

            @if ($grid->isNotEmpty())
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    @foreach ($grid as $product)
                        <a href="{{ route('products.show', $product) }}" class="card-accent block p-6">
                            @if ($product->logoMedia)
                                <img src="{{ $product->logoMedia->url() }}" alt="{{ $product->name }}" class="h-10 w-auto">
                            @endif
                            <h2 class="mt-4 font-heading text-lg font-semibold text-ink">{{ $product->name }}</h2>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $product->tagline }}</p>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($product->short_description, 110) }}</p>
                            @if ($product->company)
                                <p class="mt-3 text-xs text-ink-muted">By {{ $product->company->name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
            <div class="mt-8">{{ $products->links() }}</div>
        @endif
    </section>

    <section class="border-t border-surface-border bg-slate-50 py-20">
        <div class="container-site">
            <h2 class="font-heading text-3xl font-bold text-ink">Security &amp; infrastructure posture</h2>
            <p class="mt-3 max-w-2xl text-ink-muted">Platforms are operated by the responsible Ecozeen company. Specific certifications, uptime, and encryption claims are published only when that company has documented them.</p>
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'verified_user', 'title' => 'Access control', 'body' => 'Role-based access and administrative controls on every platform we operate.'],
                    ['icon' => 'lock', 'title' => 'Transport encryption', 'body' => 'Public sites and APIs are served over TLS. Deeper claims live on each product page.'],
                    ['icon' => 'cloud', 'title' => 'Multi-region readiness', 'body' => 'Infrastructure is designed for the markets each product actually serves.'],
                    ['icon' => 'fingerprint', 'title' => 'Account protection', 'body' => 'Authentication options follow each product’s own security model.'],
                ] as $item)
                    <div class="card p-6">
                        <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-brand-blue">
                            <x-icon :name="$item['icon']" />
                        </span>
                        <h3 class="font-heading text-base font-bold text-ink">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-muted">{{ $item['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="container-site py-20">
        <div class="rounded-2xl bg-brand-navy px-8 py-14 text-white md:px-16">
            <h2 class="font-heading text-3xl font-bold md:text-4xl">Build on Ecozeen platforms</h2>
            <p class="mt-3 max-w-xl text-slate-300">Talk to us about integration, white-label, or enterprise rollout conversations.</p>
            <a href="{{ url('/partner') }}" class="btn-secondary mt-8">Partner With Us</a>
        </div>
    </section>
</x-layouts.public>
