@php
    $heroHeading = $hero['heading'] ?? "One Group.\nMultiple Solutions.\nInfinite Possibilities.";
    $heroEyebrow = $hero['eyebrow'] ?? 'Diversified Enterprise Conglomerate';
    $heroSub = $hero['subheading'] ?? '<p>Ecozeen Group connects technology, digital asset infrastructure, digital commerce, enterprise accounting, natural resources, and agro-allied ventures to power scalable growth across Africa and the United Kingdom.</p>';
    $primaryLabel = $hero['primary_label'] ?? 'Explore Our Group';
    $primaryUrl = $hero['primary_url'] ?? route('companies.index');
    $secondaryLabel = $hero['secondary_label'] ?? 'Partner With Us';
    $secondaryUrl = $hero['secondary_url'] ?? url('/partner');
    $headingLines = preg_split("/\r\n|\n|\r/", trim($heroHeading)) ?: [$heroHeading];
    $cmsPillars = array_values(array_filter($values['items'] ?? [], 'is_array'));
    $pillars = ! empty($cmsPillars) ? $cmsPillars : $defaultPillars;
    $pillarIcons = [
        'integrity' => 'balance',
        'innovation' => 'psychology',
        'customer focus' => 'groups',
        'excellence' => 'verified_user',
        'growth' => 'trending_up',
        'long-term thinking' => 'trending_up',
        'operational excellence' => 'verified_user',
    ];
    $serviceIcons = ['cloud_sync', 'currency_exchange', 'agriculture', 'engineering'];
    $ukAddress = $settings->address_primary ?: 'Central London Financial District, United Kingdom';
    $ngAddress = $settings->address_secondary ?: 'Victoria Island Commercial Axis, Lagos, Nigeria';
@endphp
<x-layouts.public
    :seo-title="$page?->seoTitle()"
    :seo-description="$page?->seoDescription()"
    :seo-image="$page?->seoImage()?->url()"
    :no-index="$page?->no_index ?? false"
>
    {{-- HERO --}}
    <section class="relative overflow-hidden border-b border-surface-border bg-white pt-12 pb-20 md:py-24">
        <div class="container-site grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            <div class="flex flex-col items-start gap-6 lg:col-span-7">
                <span class="inline-flex items-center gap-2.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[12px] font-semibold uppercase tracking-wide text-emerald-800">
                    <span class="h-2 w-2 rounded-full bg-emerald-700" aria-hidden="true"></span>
                    {{ $heroEyebrow }}
                </span>
                <h1 class="display-xl">
                    @foreach ($headingLines as $i => $line)
                        @if ($i === count($headingLines) - 1)
                            <span class="text-brand-blue underline decoration-emerald-300 decoration-4 underline-offset-4">{{ $line }}</span>
                        @else
                            {{ $line }}<br>
                        @endif
                    @endforeach
                </h1>
                <div class="max-w-2xl text-lg leading-relaxed text-ink-muted">{!! $heroSub !!}</div>
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ $primaryUrl }}" class="btn-primary rounded-xl px-6 py-3.5 shadow-md">
                        <span>{{ $primaryLabel }}</span>
                        <x-icon name="arrow_forward" class="text-[20px]" />
                    </a>
                    <a href="{{ $secondaryUrl }}" class="btn-outline rounded-xl px-6 py-3.5 font-semibold">{{ $secondaryLabel }}</a>
                </div>
                <div class="flex w-full flex-wrap gap-x-6 gap-y-3 border-t border-surface-border pt-6 text-sm text-ink-muted">
                    <span class="inline-flex items-center gap-2"><x-icon name="verified" class="text-brand-blue text-[18px]" /> Verified Dual Jurisdiction (UK &amp; NG)</span>
                    <span class="inline-flex items-center gap-2"><x-icon name="eco" class="text-emerald-700 text-[18px]" /> Sustainable Growth Mandate</span>
                    <span class="inline-flex items-center gap-2"><x-icon name="shield" class="text-brand-blue text-[18px]" /> Enterprise Grade Governance</span>
                </div>
            </div>

            <div class="relative lg:col-span-5">
                <div class="relative overflow-hidden rounded-xl border border-surface-border bg-white p-6 shadow-level1">
                    <div class="flex items-center justify-between border-b border-surface-border pb-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-50 p-1.5">
                                <x-brand.logo-mark class="h-7 w-7" />
                            </span>
                            <div>
                                <p class="text-sm font-bold text-ink">Ecozeen Ecosystem</p>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">Real-Time Operational Mesh</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-800">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-700"></span>
                            Operational
                        </span>
                    </div>

                    <div class="space-y-4 py-6">
                        <div class="relative h-48 overflow-hidden rounded-lg border border-surface-border bg-slate-100">
                            @if ($heroImage)
                                <img src="{{ $heroImage->url() }}" alt="{{ $heroImage->alt_text ?: 'Ecozeen Group headquarters' }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-[linear-gradient(160deg,#0F172A_0%,#1e3a8a_45%,#38BDF8_100%)]"></div>
                            @endif
                            <div class="absolute inset-0 flex items-end bg-gradient-to-t from-brand-navy/80 via-transparent to-transparent p-4">
                                <p class="text-sm font-medium text-white">Headquartered in London &amp; Lagos</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-lg border border-surface-border bg-slate-50 p-3.5">
                                <p class="font-heading text-2xl font-bold text-ink">{{ $companyCount }}+</p>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">Strategic Ventures</p>
                            </div>
                            <div class="rounded-lg border border-surface-border bg-slate-50 p-3.5">
                                <p class="font-heading text-2xl font-bold text-brand-blue">{{ $productCount }}+</p>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">Digital Platforms</p>
                            </div>
                            <div class="rounded-lg border border-surface-border bg-slate-50 p-3.5">
                                <p class="font-heading text-2xl font-bold text-ink">2</p>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">Continents Operating</p>
                            </div>
                            <div class="rounded-lg border border-surface-border bg-slate-50 p-3.5">
                                <p class="font-heading text-2xl font-bold text-emerald-700">100%</p>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">Compliance Record</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-surface-border pt-4 text-[11px] text-ink-muted">
                        <span>UK Companies House &amp; NG CAC Registered</span>
                        <span class="inline-flex items-center gap-1 font-semibold text-brand-blue">
                            <x-icon name="lock" class="text-[14px]" /> Encrypted Systems
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT / MISSION / VISION / PILLARS --}}
    <section id="about" class="section-spacing border-b border-surface-border bg-white">
        <div class="container-site">
            <div class="mb-16 max-w-3xl">
                <span class="text-[12px] font-bold uppercase tracking-widest text-brand-blue">About The Holding Group</span>
                <h2 class="mt-2 font-heading text-3xl font-bold tracking-tight text-ink sm:text-4xl">Architecting Institutional Digital Capabilities for Global Impact</h2>
                <p class="mt-4 text-lg leading-relaxed text-ink-muted">Ecozeen Group is a forward-thinking conglomerate structured to capture exponential value across digital ecosystems, critical services, natural assets, and commercial trade corridors.</p>
            </div>

            <div class="mb-16 grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="relative rounded-xl border border-surface-border border-l-4 border-l-brand-blue bg-slate-50 p-8">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-brand-blue">
                        <x-icon name="rocket_launch" class="text-[28px]" />
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-ink">{{ $mission['mission_heading'] ?? 'Our Corporate Mission' }}</h3>
                    <div class="prose prose-slate mt-3 max-w-none text-ink-muted">{!! $mission['mission_text'] ?? '<p>“To deliver innovative digital solutions that empower individuals and businesses.”</p>' !!}</div>
                    <p class="mt-6 border-t border-surface-border pt-4 text-[11px] text-ink-muted">Execution via high-integrity software architecture, robust escrow infrastructure, and audited cross-border frameworks.</p>
                </div>
                <div class="relative rounded-xl border border-surface-border border-l-4 border-l-emerald-600 bg-slate-50 p-8">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                        <x-icon name="visibility" class="text-[28px]" />
                    </div>
                    <h3 class="font-heading text-xl font-semibold text-ink">{{ $mission['vision_heading'] ?? 'Our Global Vision' }}</h3>
                    <div class="prose prose-slate mt-3 max-w-none text-ink-muted">{!! $mission['vision_text'] ?? '<p>“To be a leading global brand in technology, finance, and beyond.”</p>' !!}</div>
                    <p class="mt-6 border-t border-surface-border pt-4 text-[11px] text-ink-muted">Targeting multi-sector leadership through technological sovereignty and localized ecological responsibility.</p>
                </div>
            </div>

            <h3 class="font-heading text-xl font-semibold text-ink">{{ $values['heading'] ?? 'Pillars of Operational Excellence' }}</h3>
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($pillars as $item)
                    @php
                        $icon = $item['icon'] ?? $pillarIcons[strtolower(trim($item['title'] ?? ''))] ?? 'verified';
                    @endphp
                    <div class="rounded-lg border border-surface-border bg-slate-50 p-5 transition hover:border-brand-blue">
                        <div class="mb-4 flex h-9 w-9 items-center justify-center rounded bg-white text-brand-blue shadow-sm">
                            <x-icon :name="$icon" class="text-[22px]" />
                        </div>
                        <h4 class="text-sm font-bold text-ink">{{ $item['title'] ?? '' }}</h4>
                        <p class="mt-1.5 text-[13px] leading-5 text-ink-muted">{{ $item['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- COMPANIES --}}
    @if ($companies->isNotEmpty())
        <section id="companies" class="section-spacing border-b border-surface-border bg-slate-50">
            <div class="container-site">
                <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end">
                    <div>
                        <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-brand-blue">
                            Corporate Structure
                        </div>
                        <h2 class="font-heading text-3xl font-bold text-ink">Subsidiary Directory &amp; Business Units</h2>
                        <p class="mt-1 text-ink-muted">Autonomous business divisions driven by shared governance and shared infrastructure.</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-ink-muted">
                        <span class="inline-flex items-center gap-2">
                            <x-icon name="check_circle" class="text-emerald-700 text-[18px]" />
                            All {{ $companyCount }} entities verified under active holding management
                        </span>
                        <a href="{{ route('companies.index') }}" class="btn-outline shrink-0">View all companies</a>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        @php
                            $websiteHost = $company->website_url ? preg_replace('/^www\./', '', parse_url($company->website_url, PHP_URL_HOST) ?: '') : null;
                            $external = filled($company->website_url);
                        @endphp
                        <article class="flex flex-col justify-between rounded-xl border border-surface-border bg-white p-6 transition duration-200 hover:border-brand-blue hover:shadow-level2">
                            <div>
                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <span class="rounded-md bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-brand-blue">{{ $company->category ?: \Illuminate\Support\Str::headline($company->type) }}</span>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-ink-muted">
                                        <x-icon name="verified" class="text-brand-blue text-[16px]" />
                                        {{ $company->region ?: 'Group company' }}
                                    </span>
                                </div>
                                @if ($company->logoMedia)
                                    <img src="{{ $company->logoMedia->url() }}" alt="{{ $company->name }}" class="mb-3 h-10 w-auto">
                                @endif
                                <h3 class="font-heading text-xl font-semibold text-ink">
                                    <a href="{{ route('companies.show', $company) }}" class="hover:text-brand-blue">{{ $company->name }}</a>
                                </h3>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ \Illuminate\Support\Str::limit($company->short_description, 180) }}</p>
                            </div>
                            <div class="mt-6 flex items-center justify-between border-t border-surface-border pt-6">
                                <span class="font-mono text-[11px] text-ink-muted">{{ $websiteHost ?: 'Group directory' }}</span>
                                @if ($external)
                                    <a href="{{ $company->website_url }}" rel="noopener noreferrer" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-blue hover:text-ink">
                                        Explore
                                        <x-icon name="open_in_new" class="text-[16px]" />
                                    </a>
                                @else
                                    <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-blue hover:text-ink">
                                        View profile
                                        <x-icon name="arrow_forward" class="text-[16px]" />
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PRODUCTS TABBED SPOTLIGHT --}}
    @if ($products->isNotEmpty())
        <section id="products" class="section-spacing border-b border-surface-border bg-white" x-data="{ tab: {{ $products->first()->id }} }">
            <div class="container-site">
                <div class="mx-auto mb-16 max-w-3xl text-center">
                    <span class="text-[12px] font-bold uppercase tracking-widest text-brand-blue">Platform Technologies</span>
                    <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Live Enterprise Digital Software</h2>
                    <p class="mt-3 text-ink-muted">Explore active consumer and business software solutions engineered, secured, and scaled by Ecozeen Group.</p>
                </div>
                <div class="rounded-2xl border border-surface-border bg-slate-50 p-6 shadow-level1 md:p-10">
                    <div class="mb-8 flex flex-wrap gap-2 border-b border-surface-border pb-4" role="tablist">
                        @foreach ($products as $product)
                            <button
                                type="button"
                                role="tab"
                                :aria-selected="tab === {{ $product->id }}"
                                @click="tab = {{ $product->id }}"
                                :class="tab === {{ $product->id }} ? 'bg-brand-blue text-white shadow-sm font-bold' : 'text-ink-muted hover:bg-white hover:text-ink font-medium'"
                                class="rounded-lg px-5 py-2.5 text-sm transition"
                            >
                                {{ $product->name }}
                            </button>
                        @endforeach
                    </div>
                    @foreach ($products as $product)
                        @php
                            $metrics = collect(is_array($product->metrics) ? $product->metrics : [])
                                ->map(function ($item) {
                                    if (is_array($item)) {
                                        return [
                                            'label' => $item['label'] ?? $item['title'] ?? '',
                                            'value' => $item['value'] ?? $item['description'] ?? '',
                                        ];
                                    }
                                    return null;
                                })
                                ->filter(fn ($item) => $item && ($item['label'] || $item['value']))
                                ->take(2)
                                ->values();
                            if ($metrics->isEmpty()) {
                                $metrics = collect($product->features ?? [])->take(2)->map(fn ($feature) => [
                                    'label' => 'Capability',
                                    'value' => is_array($feature) ? ($feature['title'] ?? $feature['label'] ?? '') : $feature,
                                ]);
                            }
                            $visitUrl = $product->website_url ?: route('products.show', $product);
                            $isExternal = filled($product->website_url);
                        @endphp
                        <div
                            class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 {{ $loop->first ? '' : 'hidden' }}"
                            x-bind:class="tab === {{ $product->id }} ? 'grid' : 'hidden'"
                            role="tabpanel"
                        >
                            <div class="space-y-5 lg:col-span-6">
                                <div class="inline-flex items-center gap-2 rounded bg-blue-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-brand-blue">
                                    {{ $product->tagline ?: 'Live platform' }}
                                </div>
                                <h3 class="font-heading text-2xl font-semibold text-ink">{{ $product->name }}</h3>
                                <p class="leading-relaxed text-ink-muted">{{ $product->short_description }}</p>
                                @if ($metrics->isNotEmpty())
                                    <div class="grid grid-cols-2 gap-4 py-2">
                                        @foreach ($metrics as $metric)
                                            <div class="rounded-lg border border-surface-border bg-white p-3">
                                                <span class="text-[11px] text-ink-muted">{{ $metric['label'] }}</span>
                                                <div class="mt-1 font-heading text-lg font-bold text-ink">{{ $metric['value'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="flex flex-wrap items-center gap-4 pt-2">
                                    <a href="{{ $visitUrl }}" @if ($isExternal) target="_blank" rel="noopener noreferrer" @endif class="btn-secondary rounded-xl">
                                        <span>{{ $isExternal ? 'Visit platform' : 'View product' }}</span>
                                        <x-icon :name="$isExternal ? 'launch' : 'arrow_forward'" class="text-[18px]" />
                                    </a>
                                    @if ($product->company)
                                        <span class="text-xs text-ink-muted">By {{ $product->company->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="lg:col-span-6">
                                <div class="overflow-hidden rounded-xl border border-surface-border bg-white shadow-level2">
                                    @if ($product->screenshotMedia)
                                        <img src="{{ $product->screenshotMedia->url() }}" alt="{{ $product->name }}" class="h-80 w-full object-cover">
                                    @elseif ($product->logoMedia)
                                        <div class="flex h-80 items-center justify-center bg-slate-50">
                                            <img src="{{ $product->logoMedia->url() }}" alt="{{ $product->name }}" class="max-h-24 w-auto">
                                        </div>
                                    @else
                                        <div class="flex h-80 items-center justify-center bg-[linear-gradient(135deg,#0F172A,#2563EB)] p-8 text-center text-white">
                                            <div>
                                                <p class="font-heading text-2xl font-bold">{{ $product->name }}</p>
                                                <p class="mt-2 text-sm text-blue-100">{{ $product->tagline }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('products.index') }}" class="btn-outline">Explore platform suite</a>
                </div>
            </div>
        </section>
    @endif

    {{-- SERVICES --}}
    @if ($services->isNotEmpty())
        <section id="services" class="section-spacing border-b border-surface-border bg-slate-50">
            <div class="container-site">
                <div class="mb-14 max-w-2xl">
                    <span class="text-[12px] font-bold uppercase tracking-widest text-brand-blue">Group Capabilities</span>
                    <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Cross-Industry Institutional Services</h2>
                    <p class="mt-3 text-ink-muted">Leveraging synergistic corporate divisions to execute mission-critical mandates for governments, multinational enterprises, and growth-stage ventures.</p>
                </div>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $index => $service)
                        @php($icon = $service->icon ?: ($serviceIcons[$index % count($serviceIcons)]))
                        <a href="{{ route('services.show', $service) }}" class="flex flex-col justify-between rounded-xl border border-surface-border bg-white p-6 transition hover:border-brand-blue hover:shadow-level2">
                            <div>
                                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-50 text-brand-blue">
                                    <x-icon :name="$icon" class="text-[26px]" />
                                </div>
                                <h3 class="font-heading text-lg font-semibold text-ink">{{ $service->name }}</h3>
                                <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($service->summary, 140) }}</p>
                            </div>
                            <ul class="mt-6 space-y-2 border-t border-surface-border pt-4 text-[11px] text-ink-muted">
                                <li class="flex items-center gap-2"><x-icon name="check" class="text-brand-blue text-[14px]" /> Institutional delivery</li>
                                <li class="flex items-center gap-2"><x-icon name="check" class="text-brand-blue text-[14px]" /> Group-backed execution</li>
                            </ul>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- INSIGHTS --}}
    @if ($posts->isNotEmpty())
        <section id="insights" class="section-spacing border-b border-surface-border bg-white">
            <div class="container-site">
                <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end">
                    <div>
                        <span class="text-[12px] font-bold uppercase tracking-widest text-brand-blue">Corporate Governance &amp; Research</span>
                        <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Latest Group Insights &amp; Market Intelligence</h2>
                        <p class="mt-1 text-ink-muted">Authoritative analysis from our executive leadership and policy advisors.</p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-blue hover:text-ink">
                        View All Publications
                        <x-icon name="arrow_forward" class="text-[18px]" />
                    </a>
                </div>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    @foreach ($posts as $post)
                        <a href="{{ route('blog.show', $post) }}" class="group flex flex-col overflow-hidden rounded-xl border border-surface-border bg-slate-50 transition hover:shadow-level2">
                            <div class="relative h-48 overflow-hidden bg-slate-200">
                                @if ($post->coverMedia)
                                    <img src="{{ $post->coverMedia->url() }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full bg-[linear-gradient(135deg,#0F172A,#2563EB)]"></div>
                                @endif
                                @if ($post->category)
                                    <span class="absolute left-3 top-3 rounded-md bg-white/90 px-2.5 py-1 text-[11px] font-bold text-ink backdrop-blur-sm">{{ $post->category->name }}</span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col justify-between p-6">
                                <div>
                                    <div class="mb-2.5 flex items-center gap-3 text-[11px] text-ink-muted">
                                        <span>{{ $post->published_at?->format('M j, Y') }}</span>
                                        @if ($post->reading_minutes)
                                            <span>•</span>
                                            <span>{{ $post->reading_minutes }} min read</span>
                                        @endif
                                    </div>
                                    <h3 class="font-heading text-lg font-semibold text-ink transition group-hover:text-brand-blue">{{ $post->title }}</h3>
                                    <p class="mt-2.5 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                                </div>
                                <div class="mt-6 flex items-center justify-between border-t border-surface-border pt-4 text-[11px] text-ink-muted">
                                    <span>{{ $post->author?->name ? 'By '.$post->author->name : 'Ecozeen Group' }}</span>
                                    <span class="font-bold text-brand-blue group-hover:underline">Read Article →</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PORTFOLIO CTA --}}
    <section id="portfolio" class="border-b border-surface-border bg-white py-16">
        <div class="container-site">
            <div class="flex flex-col items-center justify-between gap-8 rounded-2xl bg-brand-navy p-8 text-white shadow-level3 md:flex-row md:p-12">
                <div class="max-w-2xl space-y-3">
                    <span class="inline-block rounded bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-emerald-300">Strategic Portfolio Expansion</span>
                    <h3 class="font-heading text-3xl font-bold">Seeking High-Impact Synergies</h3>
                    <p class="leading-relaxed text-slate-200">Ecozeen Group actively deploys patient capital, technological engineering, and operational governance into scalable regional companies across our core sectors.</p>
                </div>
                <div class="flex shrink-0 flex-col gap-4 sm:flex-row">
                    <a href="#inquiry-form" class="btn-secondary rounded-xl px-6 py-3.5 text-center">Submit Venture Pitch</a>
                    <a href="{{ url('/about') }}" class="rounded-xl border border-white/30 bg-white/10 px-6 py-3.5 text-center text-sm font-medium text-white transition hover:bg-white/20">View Holding Charter</a>
                </div>
            </div>
        </div>
    </section>

    {{-- INQUIRY --}}
    <section id="inquiry-form" class="section-spacing bg-white">
        <div class="container-site grid grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-5">
                <span class="text-[12px] font-bold uppercase tracking-widest text-brand-blue">Connect With Leadership</span>
                <h2 class="font-heading text-3xl font-bold text-ink">Initiate an Institutional Dialogue</h2>
                <p class="leading-relaxed text-ink-muted">Whether you represent an institutional fund, a sovereign commercial delegation, a potential subsidiary integration, or enterprise software client, our executive secretariat will route your inquiry to the appropriate managing director.</p>
                <div class="space-y-4 pt-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-brand-blue">
                            <x-icon name="location_on" class="text-[20px]" />
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-ink">United Kingdom Headquarters</h4>
                            <p class="text-sm text-ink-muted">{{ $ukAddress }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-brand-blue">
                            <x-icon name="apartment" class="text-[20px]" />
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-ink">West Africa Regional Office</h4>
                            <p class="text-sm text-ink-muted">{{ $ngAddress }}</p>
                        </div>
                    </div>
                    @if ($settings->contact_email)
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-brand-blue">
                                <x-icon name="mail" class="text-[20px]" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-ink">Official Holding Email</h4>
                                <p class="text-sm text-ink-muted"><a href="mailto:{{ $settings->contact_email }}" class="hover:text-brand-blue">{{ $settings->contact_email }}</a></p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-surface-border bg-slate-50 p-4">
                    <x-icon name="shield_person" class="text-brand-blue text-[24px]" />
                    <p class="text-[11px] text-ink-muted">Strict Non-Disclosure Agreements (NDAs) and institutional data privacy protocols are honored on all strategic inquiries.</p>
                </div>
            </div>
            <div class="lg:col-span-7">
                <div class="rounded-2xl border border-surface-border bg-white p-8 shadow-level1">
                    <x-site.inquiry-form :companies="$inquiryCompanies" />
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
