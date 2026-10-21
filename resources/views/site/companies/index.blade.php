<x-layouts.public seo-title="Our Companies" seo-description="Explore the companies and business divisions across Ecozeen Group.">
    <section class="relative overflow-hidden border-b border-surface-border bg-white">
        <div class="absolute inset-0 bg-[radial-gradient(#c6c6cd_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>
        <div class="container-site relative py-12">
            <x-site.breadcrumbs :items="[
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Our Companies'],
            ]" />
            <div class="mt-6 max-w-3xl">
                <span class="badge-info uppercase tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-blue"></span>
                    Portfolio &amp; subsidiary ecosystem
                </span>
                <h1 class="mt-4 display-xl">Autonomous business units. Unified corporate governance.</h1>
                <p class="mt-4 max-w-2xl text-lg leading-relaxed text-ink-muted">Explore the specialized operating companies comprising Ecozeen Group, spanning technology, infrastructure, finance, and sustainable agriculture.</p>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-4 border-t border-surface-border pt-8 sm:grid-cols-4">
                <div>
                    <p class="font-heading text-2xl font-bold text-ink">{{ $companies->total() }}</p>
                    <p class="text-xs text-ink-muted">Active operating units</p>
                </div>
                <div>
                    <p class="font-heading text-2xl font-bold text-ink">{{ max(1, $regions->count()) }}</p>
                    <p class="text-xs text-ink-muted">Published regions</p>
                </div>
                <div>
                    <p class="font-heading text-2xl font-bold text-ink">{{ $categories->count() ?: '—' }}</p>
                    <p class="text-xs text-ink-muted">Sectors in directory</p>
                </div>
                <div>
                    <p class="font-heading text-2xl font-bold text-brand-blue">Group</p>
                    <p class="text-xs text-ink-muted">Shared services model</p>
                </div>
            </div>
        </div>
    </section>

    <section class="sticky top-20 z-40 border-b border-surface-border bg-white/95 backdrop-blur-md">
        <div class="container-site py-4">
            <form method="GET" class="flex flex-col items-stretch justify-between gap-4 lg:flex-row lg:items-center">
                <div class="relative max-w-md flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-muted" />
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search subsidiary, sector, or capability..." class="form-input pl-10">
                    <button type="submit" class="sr-only">Search</button>
                </div>
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0">
                    <a href="{{ route('companies.index', array_filter(['q' => request('q'), 'region' => request('region')])) }}" @class(['whitespace-nowrap rounded-full px-3.5 py-1.5 text-xs font-semibold', 'bg-brand-navy text-white' => ! request('category'), 'border border-surface-border bg-white text-ink-muted' => request('category')])>All sectors</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('companies.index', array_filter(['q' => request('q'), 'region' => request('region'), 'category' => $category])) }}" @class(['whitespace-nowrap rounded-full px-3.5 py-1.5 text-xs font-semibold', 'bg-brand-navy text-white' => request('category') === $category, 'border border-surface-border bg-white text-ink-muted hover:text-ink' => request('category') !== $category])>{{ $category }}</a>
                    @endforeach
                </div>
                <div class="inline-flex shrink-0 items-center rounded-xl border border-surface-border bg-slate-50 p-1">
                    <a href="{{ route('companies.index', array_filter(['q' => request('q'), 'category' => request('category')])) }}" @class(['rounded-lg px-3 py-1 text-[11px] font-semibold', 'bg-white text-ink shadow-sm' => ! request('region'), 'text-ink-muted' => request('region')])>All regions</a>
                    @foreach ($regions as $region)
                        <a href="{{ route('companies.index', array_filter(['q' => request('q'), 'category' => request('category'), 'region' => $region])) }}" @class(['rounded-lg px-3 py-1 text-[11px] font-semibold', 'bg-white text-ink shadow-sm' => request('region') === $region, 'text-ink-muted hover:text-ink' => request('region') !== $region])>{{ $region }}</a>
                    @endforeach
                </div>
            </form>
        </div>
    </section>

    <section class="container-site py-12">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="font-heading text-xl font-semibold text-ink">Operating directory</h2>
            <span class="text-sm text-ink-muted">Showing {{ $companies->total() }} {{ $companies->total() === 1 ? 'company' : 'companies' }}</span>
        </div>

        @if ($companies->isEmpty())
            <p class="text-ink-muted">No companies are published yet. Check back soon.</p>
        @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($companies as $company)
                    <article class="card-accent relative flex flex-col justify-between overflow-hidden p-6">
                        <div>
                            <div class="mb-4 flex items-center justify-between gap-2">
                                <span class="badge-info">
                                    <x-icon name="memory" class="text-[14px]" />
                                    {{ $company->category ?: \Illuminate\Support\Str::headline($company->type) }}
                                </span>
                                <span class="badge-success">
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-emerald"></span>
                                    {{ $company->status === 'published' ? 'Verified unit' : \Illuminate\Support\Str::headline($company->status) }}
                                </span>
                            </div>
                            @if ($company->logoMedia)
                                <img src="{{ $company->logoMedia->url() }}" alt="{{ $company->name }}" class="mb-3 h-10 w-auto">
                            @endif
                            <h3 class="font-heading text-lg font-bold text-ink">
                                <a href="{{ route('companies.show', $company) }}" class="hover:text-brand-blue">{{ $company->name }}</a>
                            </h3>
                            @if ($company->region)
                                <p class="mt-1 flex items-center gap-1 text-[11px] text-ink-muted">
                                    <x-icon name="public" class="text-[14px]" />
                                    Operating: {{ $company->region }}
                                </p>
                            @endif
                            <p class="mt-3 text-sm leading-relaxed text-ink-muted">{{ \Illuminate\Support\Str::limit($company->short_description, 140) }}</p>
                        </div>
                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-surface-border pt-4">
                            @if ($company->website_url)
                                <a href="{{ $company->website_url }}" rel="noopener noreferrer" target="_blank" class="inline-flex items-center gap-1 text-sm font-semibold text-brand-blue hover:underline">
                                    {{ parse_url($company->website_url, PHP_URL_HOST) }}
                                    <x-icon name="open_in_new" class="text-[14px]" />
                                </a>
                            @else
                                <span></span>
                            @endif
                            <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-ink hover:text-brand-blue">
                                Profile
                                <x-icon name="arrow_forward" class="text-[16px]" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-8">{{ $companies->links() }}</div>
        @endif
    </section>

    <section class="border-y border-surface-border bg-white py-16">
        <div class="container-site">
            <h2 class="font-heading text-3xl font-bold tracking-tight text-ink">How the group operates</h2>
            <p class="mt-3 max-w-2xl text-ink-muted">Operating companies stay autonomous in their markets while sharing group-level legal, security, and treasury disciplines.</p>
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'gavel', 'title' => 'Centralized legal', 'body' => 'Holding-company counsel and policy support for every subsidiary.'],
                    ['icon' => 'security', 'title' => 'Cyber & SecOps', 'body' => 'Shared security reviews without collapsing company boundaries.'],
                    ['icon' => 'account_balance', 'title' => 'Treasury & liquidity', 'body' => 'Group financial oversight that keeps operating units ring-fenced.'],
                    ['icon' => 'bolt', 'title' => 'Execution agility', 'body' => 'Independent operating teams with a common reporting cadence.'],
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

    <section class="container-site py-12">
        <div class="rounded-2xl bg-brand-navy px-8 py-12 text-white sm:px-12">
            <h3 class="font-heading text-3xl font-bold tracking-tight">Looking to partner with a subsidiary?</h3>
            <p class="mt-2 max-w-xl text-slate-300">Tell us which company you want to work with and we will route the enquiry.</p>
            <a href="{{ route('contact') }}" class="btn-secondary mt-6">Start a conversation</a>
        </div>
    </section>
</x-layouts.public>
