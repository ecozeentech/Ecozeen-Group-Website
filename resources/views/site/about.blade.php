@php
    $heroHeading = $hero['heading'] ?? $page->title;
    $heroEyebrow = $hero['eyebrow'] ?? 'Institutional overview & governance';
    $heroSub = $hero['subheading'] ?? ($intro['content'] ?? '<p>Ecozeen Group is a diversified holding company overseeing operating businesses across financial technology, enterprise IT services, leisure, mining, and agriculture.</p>');
    $pillars = array_values(array_filter($values['items'] ?? [], 'is_array'));
    if (empty($pillars)) {
        $pillars = [
            ['title' => 'Integrity', 'description' => 'We operate with transparency and accountability across every business we own.', 'icon' => 'gavel'],
            ['title' => 'Innovation', 'description' => 'We support practical technology that improves how our companies serve their markets.', 'icon' => 'lightbulb'],
            ['title' => 'Customer focus', 'description' => 'We design and operate around the people and institutions who use our products and services.', 'icon' => 'groups'],
            ['title' => 'Excellence', 'description' => 'We support our operating companies with the discipline to execute well.', 'icon' => 'military_tech'],
            ['title' => 'Sustainable growth', 'description' => 'We build for durability, not short-term results.', 'icon' => 'trending_up'],
        ];
    }
    $pillarIcons = ['gavel', 'lightbulb', 'groups', 'military_tech', 'trending_up'];
@endphp
<x-layouts.public
    :seo-title="$page->seoTitle()"
    :seo-description="$page->seoDescription()"
    :seo-image="$page->seoImage()?->url()"
    :seo-canonical="$page->canonical_url"
    :no-index="$page->no_index"
>
    <section class="border-b border-surface-border bg-white">
        <div class="container-site flex flex-wrap items-center justify-between gap-4 py-6">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'About Us'],
            ]" />
            <span class="badge-info uppercase tracking-wider">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-blue"></span>
                {{ $heroEyebrow }}
            </span>
        </div>
    </section>

    <section class="relative overflow-hidden bg-gradient-to-b from-white via-slate-50 to-surface-canvas py-16">
        <div class="container-site grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
            <div class="flex flex-col gap-6 lg:col-span-7">
                <p class="text-eyebrow">
                    <x-icon name="corporate_fare" class="text-[18px]" />
                    Global conglomerate structure
                </p>
                <h1 class="display-xl">{{ $heroHeading }}</h1>
                <div class="max-w-2xl text-lg leading-relaxed text-ink-muted">{!! $heroSub !!}</div>
                <div class="grid grid-cols-3 gap-6 border-t border-surface-border pt-6">
                    <div>
                        <p class="font-heading text-2xl font-bold text-brand-blue">UK &amp; NG</p>
                        <p class="text-xs text-ink-muted">Operating footprint</p>
                    </div>
                    <div>
                        <p class="font-heading text-2xl font-bold text-ink">{{ $companyCount }}</p>
                        <p class="text-xs text-ink-muted">Operating companies</p>
                    </div>
                    <div>
                        <p class="font-heading text-2xl font-bold text-emerald-700">Group</p>
                        <p class="text-xs text-ink-muted">Holding governance</p>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-5">
                <div class="relative overflow-hidden rounded-2xl border border-surface-border bg-white p-2 shadow-level1">
                    @if ($page->seoImage())
                        <img src="{{ $page->seoImage()->url() }}" alt="{{ $page->title }}" class="h-[360px] w-full rounded-xl object-cover">
                    @else
                        <div class="flex h-[360px] items-center justify-center rounded-xl bg-slate-900">
                            <x-brand.logo-mark class="h-24 w-24 brightness-0 invert" />
                        </div>
                    @endif
                    <div class="absolute inset-x-6 bottom-6 flex items-center justify-between rounded-xl border border-surface-border bg-white/95 p-4 shadow-level1 backdrop-blur">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-brand-blue">
                                <x-icon name="verified_user" />
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-ink">Institutional-grade holdings</p>
                                <p class="text-[11px] text-ink-muted">Group oversight of operating companies</p>
                            </div>
                        </div>
                        <span class="badge-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-white">
        <div class="container-site">
            <div class="mx-auto mb-12 max-w-3xl text-center">
                <p class="text-eyebrow justify-center">Strategic anchors</p>
                <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Driven by purpose, guided by long-term vision</h2>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <div class="relative flex flex-col justify-between rounded-2xl border border-surface-border border-t-4 border-t-brand-blue bg-white p-8">
                    <div>
                        <div class="mb-6 flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-brand-blue">
                                <x-icon name="track_changes" class="text-[28px]" />
                            </span>
                            <span class="badge-info">Primary mandate</span>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-ink">{{ $mission['mission_heading'] ?? 'Our Corporate Mission' }}</h3>
                        <div class="prose prose-slate mt-4 max-w-none">{!! $mission['mission_text'] ?? '<p>To build and support businesses that create lasting value for the people and markets they serve.</p>' !!}</div>
                    </div>
                </div>
                <div class="relative flex flex-col justify-between rounded-2xl border border-surface-border border-t-4 border-t-brand-emerald bg-white p-8">
                    <div>
                        <div class="mb-6 flex items-center justify-between">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-800">
                                <x-icon name="public" class="text-[28px]" />
                            </span>
                            <span class="badge-success">Long-term horizon</span>
                        </div>
                        <h3 class="font-heading text-xl font-bold text-ink">{{ $mission['vision_heading'] ?? 'Our Global Vision' }}</h3>
                        <div class="prose prose-slate mt-4 max-w-none">{!! $mission['vision_text'] ?? '<p>A diversified group recognized for disciplined operations and long-term thinking across every business we own.</p>' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing bg-slate-50">
        <div class="container-site">
            <div class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-eyebrow">Foundational values</p>
                    <h2 class="mt-2 font-heading text-3xl font-bold text-ink">{{ $values['heading'] ?? 'The core pillars of Ecozeen Group' }}</h2>
                </div>
                <p class="max-w-md text-sm text-ink-muted">Principles that steer the holding company and the operating units it supports.</p>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($pillars as $index => $item)
                    <div @class(['card p-6', 'md:col-span-2 lg:col-span-2' => $index === count($pillars) - 1 && count($pillars) % 3 !== 0])>
                        <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-brand-blue">
                            <x-icon :name="$item['icon'] ?? ($pillarIcons[$index] ?? 'check_circle')" />
                        </span>
                        <h3 class="font-heading text-lg font-bold text-ink">{{ $index + 1 }}. {{ $item['title'] ?? '' }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $item['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-spacing bg-white">
        <div class="container-site">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <p class="text-eyebrow justify-center">Global footprint</p>
                <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Dual-jurisdiction operating model</h2>
                <p class="mt-2 text-ink-muted">The group is structured around United Kingdom and West African operations, with each subsidiary managed in its own market.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div class="flex flex-col justify-between rounded-2xl border border-surface-border bg-white p-8">
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="badge-info"><x-icon name="apartment" class="text-[14px]" /> London hub</span>
                                <h3 class="mt-3 font-heading text-xl font-bold text-ink">United Kingdom operations</h3>
                                <p class="mt-1 text-sm text-ink-muted">Capital strategy, group governance, and technology oversight.</p>
                            </div>
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 font-heading font-bold text-ink">UK</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-sm text-ink">
                            <li class="flex items-start gap-2"><x-icon name="check_circle" class="mt-0.5 text-brand-blue text-[18px]" /> Group-level capital allocation and partner underwriting.</li>
                            <li class="flex items-start gap-2"><x-icon name="check_circle" class="mt-0.5 text-brand-blue text-[18px]" /> Holding-company governance and reporting discipline.</li>
                            <li class="flex items-start gap-2"><x-icon name="check_circle" class="mt-0.5 text-brand-blue text-[18px]" /> Enterprise software standards and security reviews.</li>
                        </ul>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-surface-border pt-6 text-xs text-ink-muted">
                        <span>United Kingdom</span>
                        <span class="font-semibold text-brand-blue">Corporate directorate</span>
                    </div>
                </div>
                <div class="flex flex-col justify-between rounded-2xl border border-surface-border bg-white p-8">
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="badge-success"><x-icon name="domain" class="text-[14px]" /> Lagos &amp; regional hubs</span>
                                <h3 class="mt-3 font-heading text-xl font-bold text-ink">West African &amp; Nigerian operations</h3>
                                <p class="mt-1 text-sm text-ink-muted">Digital services, agro-allied corridors, and operating subsidiaries.</p>
                            </div>
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 font-heading font-bold text-emerald-800">NG</span>
                        </div>
                        <ul class="mt-6 space-y-3 text-sm text-ink">
                            <li class="flex items-start gap-2"><x-icon name="check_circle" class="mt-0.5 text-emerald-700 text-[18px]" /> Consumer and enterprise technology platforms.</li>
                            <li class="flex items-start gap-2"><x-icon name="check_circle" class="mt-0.5 text-emerald-700 text-[18px]" /> Agro-commodity value chains and producer aggregation.</li>
                            <li class="flex items-start gap-2"><x-icon name="check_circle" class="mt-0.5 text-emerald-700 text-[18px]" /> Regional logistics and supply-chain operations.</li>
                        </ul>
                    </div>
                    <div class="mt-6 flex items-center justify-between border-t border-surface-border pt-6 text-xs text-ink-muted">
                        <span>Nigeria &amp; West Africa</span>
                        <span class="font-semibold text-emerald-800">Operating subsidiaries</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing">
        <div class="container-site">
            <div class="rounded-3xl bg-brand-navy p-8 text-white md:p-14">
                <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12">
                    <div class="lg:col-span-7">
                        <p class="inline-flex items-center gap-2 rounded-full border border-white/20 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-emerald-300">
                            <x-icon name="shield" class="text-[16px]" /> Group governance
                        </p>
                        <h2 class="mt-6 font-heading text-3xl font-bold">Institutional governance &amp; compliance integrity</h2>
                        <p class="mt-4 max-w-2xl text-slate-300">Ecozeen Group maintains ring-fenced subsidiary structures. Each operating company works under its own statutory mandate while benefiting from group-wide oversight and a shared privacy standard.</p>
                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 text-sm">
                                <x-icon name="verified" class="text-cyan-300" /> Independent audit cadence
                            </div>
                            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 text-sm">
                                <x-icon name="security" class="text-emerald-300" /> Ring-fenced liability structure
                            </div>
                            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 text-sm">
                                <x-icon name="rule" class="text-cyan-300" /> AML &amp; sanctions screening
                            </div>
                            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 text-sm">
                                <x-icon name="lock" class="text-emerald-300" /> UK GDPR &amp; NDPA alignment
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-4 lg:col-span-5">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                            <h3 class="font-heading text-lg font-semibold">Subsidiary segregation</h3>
                            <p class="mt-2 text-sm text-slate-300">Holding governance keeps capital and liability boundaries between technology, trading, and consumer platforms.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                            <h3 class="font-heading text-lg font-semibold">Ethics &amp; reporting</h3>
                            <p class="mt-2 text-sm text-slate-300">Oversight channels route to the holding company so operating issues can be reviewed independently of day-to-day management.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($companies->isNotEmpty())
        <section class="section-spacing bg-white">
            <div class="container-site">
                <div class="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                    <div>
                        <p class="text-eyebrow">Operating portfolio</p>
                        <h2 class="mt-2 font-heading text-3xl font-bold text-ink">Companies across the group</h2>
                    </div>
                    <a href="{{ route('companies.index') }}" class="btn-outline">View directory</a>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($companies as $company)
                        <a href="{{ route('companies.show', $company) }}" class="card-accent block p-6">
                            <h3 class="font-heading text-lg font-semibold text-ink">{{ $company->name }}</h3>
                            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $company->category ?: \Illuminate\Support\Str::headline($company->type) }}</p>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($company->short_description, 110) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-spacing border-t border-surface-border bg-slate-50">
        <div class="container-site">
            <div class="flex flex-col items-center justify-between gap-8 rounded-2xl border border-surface-border bg-gradient-to-r from-slate-50 to-blue-50 p-8 md:flex-row md:p-12">
                <div class="max-w-xl">
                    <p class="text-eyebrow">Institutional synergy</p>
                    <h3 class="mt-1 font-heading text-3xl font-bold text-ink">Collaborate with Ecozeen Group</h3>
                    <p class="mt-2 text-ink-muted">Whether discussing capital, enterprise digital solutions, or operating partnerships, we invite a conversation.</p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary">Initiate inquiries</a>
                    <a href="{{ route('portfolio.index') }}" class="btn-outline">View portfolio</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
