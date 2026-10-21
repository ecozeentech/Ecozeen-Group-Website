@php
    $heroHeading = $hero['heading'] ?? 'Forge Strategic Synergies with Ecozeen Group';
    $heroEyebrow = $hero['eyebrow'] ?? 'Institutional alliances & venture acceleration';
    $heroSub = $hero['subheading'] ?? '<p>We partner with institutions, operators, and enterprises that share a long-term view of building durable businesses across the United Kingdom and West Africa.</p>';
    $tracks = array_values(array_filter($features['items'] ?? [], 'is_array'));
    if (count($tracks) < 4) {
        $tracks = [
            ['title' => 'Technology & software co-ventures', 'description' => 'Integrate with Ecozeen technology companies, white-label infrastructure, and custom software for cross-border workflows.', 'icon' => 'code_blocks', 'cta' => 'Explore a tech alliance'],
            ['title' => 'Institutional liquidity & trading rails', 'description' => 'Discuss treasury, settlement, and digital-asset operating partnerships with the relevant group companies.', 'icon' => 'currency_exchange', 'cta' => 'Apply for a desk conversation'],
            ['title' => 'Agro-allied & commodity supply chains', 'description' => 'Explore contract farming, warehouse, and off-take collaborations with Ecozeen Agro Allied.', 'icon' => 'agriculture', 'cta' => 'Review agro-allied terms'],
            ['title' => 'Strategic capital & joint ventures', 'description' => 'Co-investment, SPV structures, and long-term ownership conversations at group level.', 'icon' => 'corporate_fare', 'cta' => 'Engage the capital desk'],
        ];
    }
    $trackIcons = ['code_blocks', 'currency_exchange', 'agriculture', 'corporate_fare'];
@endphp
<x-layouts.public
    :seo-title="$page->seoTitle()"
    :seo-description="$page->seoDescription()"
    :seo-image="$page->seoImage()?->url()"
    :seo-canonical="$page->canonical_url"
    :no-index="$page->no_index"
>
    <section class="relative overflow-hidden border-b border-surface-border bg-gradient-to-b from-white via-slate-50 to-surface-canvas pt-12 pb-20 lg:pt-20 lg:pb-28">
        <div class="container-site grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            <div class="flex flex-col items-start gap-6 lg:col-span-7">
                <span class="inline-flex items-center gap-2.5 rounded-full border border-brand-blue/30 bg-white px-3 py-1.5">
                    <span class="h-2 w-2 rounded-full bg-brand-blue"></span>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-brand-blue">{{ $heroEyebrow }}</span>
                </span>
                <h1 class="display-xl">{{ $heroHeading }}</h1>
                <div class="max-w-2xl text-lg leading-relaxed text-ink-muted">{!! $heroSub !!}</div>
                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    <a href="#intake-portal" class="btn-primary">
                        Submit partnership proposal
                        <x-icon name="send" class="text-[18px]" />
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline">Contact Ecozeen Group</a>
                </div>
                <div class="flex flex-wrap gap-6 pt-4 text-xs text-ink-muted">
                    <span class="inline-flex items-center gap-1.5"><x-icon name="verified" class="text-brand-blue text-[16px]" /> UK &amp; Nigeria operating footprint</span>
                    <span class="inline-flex items-center gap-1.5"><x-icon name="lock" class="text-brand-blue text-[16px]" /> Encrypted inquiry intake</span>
                    <span class="inline-flex items-center gap-1.5"><x-icon name="speed" class="text-brand-blue text-[16px]" /> Routed to the relevant team</span>
                </div>
            </div>
            <div class="lg:col-span-5">
                <div class="relative overflow-hidden rounded-xl border border-surface-border bg-white p-6 shadow-level1 sm:p-8">
                    <div class="absolute inset-x-0 top-0 h-1 bg-brand-blue"></div>
                    <div class="flex items-center justify-between border-b border-surface-border pb-6">
                        <div class="flex items-center gap-3">
                            <x-icon name="hub" class="text-brand-blue text-[28px]" />
                            <div>
                                <h3 class="font-heading text-lg font-bold text-ink">Corridor operations</h3>
                                <p class="text-[11px] uppercase tracking-wider text-ink-muted">United Kingdom ⇄ West Africa</p>
                            </div>
                        </div>
                        <span class="badge-info">Active</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 py-6">
                        <div class="rounded-lg border border-surface-border bg-slate-50 p-4">
                            <p class="text-[11px] uppercase tracking-wider text-ink-muted">Operating companies</p>
                            <p class="mt-1 font-heading text-2xl font-bold text-ink">{{ $companyCount }}</p>
                            <p class="mt-0.5 text-[11px] text-brand-blue">Live directory</p>
                        </div>
                        <div class="rounded-lg border border-surface-border bg-slate-50 p-4">
                            <p class="text-[11px] uppercase tracking-wider text-ink-muted">Partnership tracks</p>
                            <p class="mt-1 font-heading text-2xl font-bold text-ink">{{ count($tracks) }}</p>
                            <p class="mt-0.5 text-[11px] text-brand-blue">Engagement paths</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-lg border border-surface-border bg-slate-50 p-3.5">
                        <x-icon name="account_balance" class="text-brand-blue text-[22px]" />
                        <p class="text-sm text-ink">Institutional conversations are reviewed by the group and routed to the operating company that should own the relationship.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tracks" class="section-spacing">
        <div class="container-site">
            <div class="mx-auto mb-16 max-w-3xl text-center">
                <span class="badge-info uppercase tracking-wider">{{ $features['heading'] ?? 'Synergy architecture' }}</span>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink">{{ $features['heading'] ?? 'Four institutional co-venture tracks' }}</h2>
                <p class="mt-3 text-ink-muted">{{ $features['subheading'] ?? 'Choose the alignment that matches how you want to work with Ecozeen Group. Every proposal is routed to a dedicated team.' }}</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                @foreach ($tracks as $index => $track)
                    <div class="card-accent relative flex flex-col justify-between p-8">
                        <div>
                            <div class="mb-6 flex items-center justify-between">
                                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-brand-blue">
                                    <x-icon :name="$track['icon'] ?? ($trackIcons[$index] ?? 'hub')" class="text-[28px]" />
                                </span>
                                <span class="text-[11px] font-semibold tracking-wider text-ink-muted">Track {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <h3 class="font-heading text-xl font-bold text-ink">{{ $track['title'] ?? '' }}</h3>
                            <p class="mt-3 text-ink-muted">{{ $track['description'] ?? '' }}</p>
                        </div>
                        <a href="#intake-portal" class="mt-8 inline-flex items-center justify-between border-t border-surface-border pt-4 text-sm font-semibold text-brand-blue">
                            <span>{{ $track['cta'] ?? 'Start this conversation' }}</span>
                            <x-icon name="arrow_forward" class="text-[18px]" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-spacing border-y border-surface-border bg-slate-50">
        <div class="container-site">
            <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <span class="badge-neutral uppercase tracking-wider">Institutional distinction</span>
                    <h2 class="mt-3 font-heading text-3xl font-extrabold text-ink">The Ecozeen institutional advantage</h2>
                </div>
                <p class="max-w-md text-ink-muted">Built to reduce operational friction when bridging UK-based holding governance with West African operating businesses.</p>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'gavel', 'title' => 'Dual-jurisdiction structure', 'body' => 'Holding governance in the United Kingdom with operating companies in West Africa, each under its own statutory mandate.'],
                    ['icon' => 'payments', 'title' => 'Shared treasury discipline', 'body' => 'Group-level financial oversight supporting operating companies without collapsing their legal boundaries.'],
                    ['icon' => 'rocket_launch', 'title' => 'Rapid deployment stack', 'body' => 'Existing cloud, accounting, and CMS platforms that shorten time-to-market for new collaborations.'],
                    ['icon' => 'public', 'title' => 'Regional operating reach', 'body' => 'Commercial networks spanning London, Lagos, and primary West African trade corridors.'],
                ] as $advantage)
                    <div class="card p-6">
                        <span class="mb-5 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-brand-blue">
                            <x-icon :name="$advantage['icon']" />
                        </span>
                        <h3 class="font-heading text-base font-bold text-ink">{{ $advantage['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $advantage['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-spacing">
        <div class="container-site">
            <div class="mx-auto mb-16 max-w-3xl text-center">
                <span class="badge-info uppercase tracking-wider">Governance framework</span>
                <h2 class="mt-3 font-heading text-3xl font-extrabold text-ink">Institutional onboarding timeline</h2>
                <p class="mt-2 text-ink-muted">A structured four-phase pipeline so every partnership conversation has a clear next step.</p>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                @foreach ([
                    ['title' => 'Scope review & NDA', 'body' => 'Initial intake, executive review, and a reciprocal non-disclosure agreement when the conversation requires one.'],
                    ['title' => 'Due diligence', 'body' => 'Fiduciary, technical, and governance checks aligned with the operating company that would host the relationship.'],
                    ['title' => 'SLA & term sheet', 'body' => 'Commercial terms, service expectations, and the legal vehicle for the collaboration.'],
                    ['title' => 'Integration & launch', 'body' => 'Dedicated engineering or operations support, live telemetry where relevant, and a public announcement when both sides agree.'],
                ] as $i => $step)
                    <div class="card p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-blue text-sm font-bold text-white">{{ $i + 1 }}</span>
                            <span class="text-[11px] uppercase text-ink-muted">Phase {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3 class="font-heading text-base font-bold text-ink">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-muted">{{ $step['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="intake-portal" class="section-spacing border-t border-surface-border bg-slate-50">
        <div class="mx-auto max-w-4xl px-4 sm:px-6">
            <div class="overflow-hidden rounded-2xl border border-surface-border bg-white shadow-level1">
                <div class="flex flex-col justify-between gap-4 border-b border-surface-border bg-slate-50 p-8 sm:flex-row sm:items-center sm:p-10">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-brand-blue">Official intake portal</p>
                        <h3 class="mt-1 font-heading text-2xl font-bold text-ink">Dispatch institutional proposal</h3>
                        <p class="mt-1 text-sm text-ink-muted">Submissions are reviewed by Ecozeen Group and routed to the relevant operating company.</p>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-surface-border bg-white px-3 py-1.5 text-xs font-semibold text-ink">
                        <x-icon name="shield" class="text-brand-blue text-[18px]" /> Secure protocol
                    </span>
                </div>
                <div class="p-8 sm:p-10">
                    @if (session('status'))
                        <p class="mb-4 rounded-token border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</p>
                    @endif
                    <x-site.inquiry-form :companies="$companies" heading="Partnership inquiry type" submit-label="Dispatch institutional proposal" default-type="business_partnership" />
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
