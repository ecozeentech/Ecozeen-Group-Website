@php
    $heroHeading = $hero['heading'] ?? "One Group.\nMultiple Solutions.\nInfinite Possibilities.";
    $heroEyebrow = $hero['eyebrow'] ?? 'Diversified Enterprise Conglomerate';
    $heroSub = $hero['subheading'] ?? '<p>Ecozeen Group oversees a portfolio of operating businesses spanning financial technology, enterprise IT services, leisure, mining, and agriculture.</p>';
    $primaryLabel = $hero['primary_label'] ?? 'Explore Our Group';
    $primaryUrl = $hero['primary_url'] ?? route('companies.index');
    $secondaryLabel = $hero['secondary_label'] ?? 'Partner With Us';
    $secondaryUrl = $hero['secondary_url'] ?? url('/partner');
@endphp
<x-layouts.public
    :seo-title="$page?->seoTitle()"
    :seo-description="$page?->seoDescription()"
    :seo-image="$page?->seoImage()?->url()"
    :no-index="$page?->no_index ?? false"
>
    <section class="relative overflow-hidden border-b border-surface-border bg-white pt-12 pb-20 md:py-24">
        <div class="container-site grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            <div class="flex flex-col items-start gap-6 lg:col-span-7">
                <span class="text-eyebrow">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-emerald" aria-hidden="true"></span>
                    {{ $heroEyebrow }}
                </span>
                <h1 class="display-xl">
                    @foreach (preg_split("/\r\n|\n|\r/", $heroHeading) as $i => $line)
                        @if ($i === count(preg_split("/\r\n|\n|\r/", $heroHeading)) - 1)
                            <span class="text-brand-blue underline decoration-brand-emerald decoration-4 underline-offset-4">{{ $line }}</span>
                        @else
                            {{ $line }}<br>
                        @endif
                    @endforeach
                </h1>
                <div class="max-w-2xl text-lg leading-relaxed text-ink-muted">{!! $heroSub !!}</div>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ $primaryUrl }}" class="btn-primary">
                        {{ $primaryLabel }}
                        <x-icon name="arrow_forward" class="text-[18px]" />
                    </a>
                    <a href="{{ $secondaryUrl }}" class="btn-outline">{{ $secondaryLabel }}</a>
                </div>
                <div class="flex w-full flex-wrap gap-x-6 gap-y-3 border-t border-surface-border pt-6 text-sm text-ink-muted">
                    <span class="inline-flex items-center gap-2"><x-icon name="verified" class="text-brand-blue text-[18px]" /> Long-term ownership</span>
                    <span class="inline-flex items-center gap-2"><x-icon name="eco" class="text-brand-emerald text-[18px]" /> Sustainable growth mandate</span>
                    <span class="inline-flex items-center gap-2"><x-icon name="shield" class="text-brand-blue text-[18px]" /> Disciplined group governance</span>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-xl border border-surface-border bg-white p-6 shadow-level1">
                    <div class="flex items-center justify-between border-b border-surface-border pb-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-50 p-1.5">
                                <x-brand.logo-mark class="h-7 w-7" />
                            </span>
                            <div>
                                <p class="text-sm font-bold text-ink">Ecozeen Ecosystem</p>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">Operating portfolio</p>
                            </div>
                        </div>
                        <span class="badge-success">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-emerald"></span>
                            Live
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 py-6">
                        <div class="rounded-lg border border-surface-border bg-slate-50 p-3.5">
                            <p class="font-heading text-2xl font-bold text-ink">{{ $companyCount }}</p>
                            <p class="text-[11px] uppercase tracking-wider text-ink-muted">Operating companies</p>
                        </div>
                        <div class="rounded-lg border border-surface-border bg-slate-50 p-3.5">
                            <p class="font-heading text-2xl font-bold text-brand-blue">{{ $productCount }}</p>
                            <p class="text-[11px] uppercase tracking-wider text-ink-muted">Digital platforms</p>
                        </div>
                    </div>
                    @if ($companies->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach ($companies->take(4) as $company)
                                <li>
                                    <a href="{{ route('companies.show', $company) }}" class="flex items-center justify-between rounded-lg px-2 py-1.5 text-sm hover:bg-slate-50">
                                        <span class="font-medium text-ink">{{ $company->name }}</span>
                                        <x-icon name="chevron_right" class="text-ink-muted text-[18px]" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="section-spacing bg-white">
        <div class="container-site">
            <p class="text-eyebrow">About the holding group</p>
            <h2 class="mt-3 max-w-3xl font-heading text-3xl font-bold tracking-tight text-ink sm:text-4xl">
                {{ $mission['mission_heading'] ?? 'Our Mission' }}
            </h2>
            <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-xl border border-surface-border border-l-4 border-l-brand-blue bg-white p-8">
                    <p class="text-eyebrow">Mission</p>
                    <h3 class="mt-2 font-heading text-xl font-bold text-ink">{{ $mission['mission_heading'] ?? 'Our Mission' }}</h3>
                    <div class="prose prose-slate mt-3 max-w-none">{!! $mission['mission_text'] ?? '<p>To build and support businesses that create lasting value for the people and markets they serve.</p>' !!}</div>
                </div>
                <div class="rounded-xl border border-surface-border border-l-4 border-l-brand-emerald bg-white p-8">
                    <p class="text-eyebrow">Vision</p>
                    <h3 class="mt-2 font-heading text-xl font-bold text-ink">{{ $mission['vision_heading'] ?? 'Our Vision' }}</h3>
                    <div class="prose prose-slate mt-3 max-w-none">{!! $mission['vision_text'] ?? '<p>A diversified group recognized for disciplined operations and long-term thinking across every business we own.</p>' !!}</div>
                </div>
            </div>
            @php($pillars = array_filter($values['items'] ?? [], 'is_array'))
            @if (! empty($pillars))
                <h3 class="mt-14 font-heading text-2xl font-bold text-ink">{{ $values['heading'] ?? 'What guides us' }}</h3>
                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pillars as $item)
                        <div class="card-accent p-6">
                            <h4 class="font-heading text-base font-semibold text-ink">{{ $item['title'] ?? '' }}</h4>
                            <p class="mt-2 text-sm text-ink-muted">{{ $item['description'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if ($companies->isNotEmpty())
        <section id="companies" class="section-spacing bg-slate-50">
            <div class="container-site">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-eyebrow">Corporate structure</p>
                        <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Subsidiary directory &amp; business units</h2>
                    </div>
                    <a href="{{ route('companies.index') }}" class="btn-outline shrink-0">View all companies</a>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        <a href="{{ route('companies.show', $company) }}" class="card-accent block p-6">
                            @if ($company->logoMedia)
                                <img src="{{ $company->logoMedia->url() }}" alt="{{ $company->name }}" class="h-10 w-auto">
                            @endif
                            <h3 class="mt-4 font-heading text-lg font-semibold text-ink">{{ $company->name }}</h3>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $company->category ?: \Illuminate\Support\Str::headline($company->type) }}</p>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($company->short_description, 120) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($products->isNotEmpty())
        <section id="products" class="section-spacing bg-white">
            <div class="container-site">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-eyebrow justify-center">Live enterprise software</p>
                    <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Products &amp; digital platforms</h2>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product) }}" class="card-accent block p-6">
                            @if ($product->logoMedia)
                                <img src="{{ $product->logoMedia->url() }}" alt="{{ $product->name }}" class="h-10 w-auto">
                            @endif
                            <h3 class="mt-4 font-heading text-lg font-semibold text-ink">{{ $product->name }}</h3>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $product->tagline }}</p>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($product->short_description, 110) }}</p>
                            @if ($product->company)
                                <p class="mt-3 text-xs text-ink-muted">By {{ $product->company->name }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('products.index') }}" class="btn-outline">Explore platform suite</a>
                </div>
            </div>
        </section>
    @endif

    @if ($services->isNotEmpty())
        <section id="services" class="section-spacing bg-slate-50">
            <div class="container-site">
                <h2 class="font-heading text-3xl font-bold text-ink">Cross-industry institutional services</h2>
                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <a href="{{ route('services.show', $service) }}" class="card-accent block p-6">
                            <h3 class="font-heading text-base font-semibold text-ink">{{ $service->name }}</h3>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($service->summary, 110) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($posts->isNotEmpty())
        <section id="insights" class="section-spacing bg-white">
            <div class="container-site">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-eyebrow">Strategic intelligence</p>
                        <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Latest group insights</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn-outline">View all publications</a>
                </div>
                <div class="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <a href="{{ route('blog.show', $post) }}" class="card-hover overflow-hidden">
                            @if ($post->coverMedia)
                                <img src="{{ $post->coverMedia->url() }}" alt="{{ $post->title }}" class="h-44 w-full object-cover">
                            @endif
                            <div class="p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $post->category?->name }}</p>
                                <h3 class="mt-1 font-heading text-base font-semibold text-ink">{{ $post->title }}</h3>
                                <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 110) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-spacing">
        <div class="container-site">
            <div class="rounded-2xl bg-brand-navy px-8 py-14 text-center text-white sm:px-16">
                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-300">Partnerships</p>
                <h2 class="mt-3 font-heading text-3xl font-bold">Seeking high-impact synergies</h2>
                <p class="mx-auto mt-3 max-w-xl text-slate-200">Reach out to discuss investment, partnership, or enterprise service opportunities.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ url('/partner') }}" class="btn-secondary">Partner With Us</a>
                    <a href="{{ route('contact') }}" class="btn-outline border-white/30 bg-transparent text-white hover:bg-white/10">Contact Ecozeen Group</a>
                </div>
            </div>
        </div>
    </section>

    <section id="inquiry-form" class="section-spacing bg-slate-50">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="text-eyebrow">Official communications</p>
                <h2 class="mt-3 font-heading text-3xl font-bold text-ink">Initiate an institutional dialogue</h2>
                <p class="mt-4 text-ink-muted">Use the form to reach the relevant Ecozeen Group team. Contact details can be published by an administrator in Website Settings.</p>
            </div>
            <div class="lg:col-span-7">
                <div class="card p-6 sm:p-8">
                    @if (session('status'))
                        <p class="mb-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</p>
                    @endif
                    <x-site.inquiry-form :companies="$inquiryCompanies" />
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
