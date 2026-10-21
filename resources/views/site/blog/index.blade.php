@php
    $items = $posts->getCollection();
    $featured = ($posts->onFirstPage() && ! isset($activeCategory) && ! isset($activeTag) && ! request('q')) ? $items->first() : null;
    $grid = $featured ? $items->slice(1) : $items;
    $heading = isset($activeCategory) ? $activeCategory->name : (isset($activeTag) ? $activeTag->name : 'Executive Insights & Strategic Intelligence');
@endphp
<x-layouts.public seo-title="Insights" seo-description="News, analysis, and perspectives from across Ecozeen Group.">
    <section class="border-b border-surface-border bg-white pt-12 pb-8 md:pt-16 md:pb-12">
        <div class="container-site">
            <div class="mb-4 flex items-center gap-2">
                <span class="badge-info uppercase tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-blue"></span>
                    Official corporate editorial
                </span>
                <span class="text-[11px] text-ink-muted">ecozeengroup.com/insights</span>
            </div>
            <div class="mb-8 max-w-4xl">
                <h1 class="display-xl">{{ $heading }}</h1>
                <p class="mt-4 text-lg leading-relaxed text-ink-muted">{{ isset($activeCategory) ? 'Articles in this category.' : (isset($activeTag) ? 'Articles with this tag.' : 'Deep analysis, technological breakthroughs, and corporate dispatches from across the Ecozeen Group ecosystem.') }}</p>
            </div>
            <form method="GET" action="{{ isset($activeCategory) ? route('blog.category', $activeCategory) : route('blog.index') }}" class="space-y-4">
                <div class="relative max-w-2xl">
                    <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted" />
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search perspectives, research papers, market dispatches..." class="form-input h-12 pl-12">
                </div>
                @if ($categories->isNotEmpty())
                    <div class="flex items-center gap-2 overflow-x-auto pb-2">
                        <a href="{{ route('blog.index') }}" @class(['shrink-0 rounded-xl px-4 py-2 text-sm font-semibold', 'bg-brand-navy text-white shadow-sm' => ! isset($activeCategory), 'border border-surface-border bg-slate-50 text-ink hover:bg-slate-100' => isset($activeCategory)])>All perspectives</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('blog.category', $category) }}" @class(['shrink-0 rounded-xl px-4 py-2 text-sm font-semibold', 'bg-brand-navy text-white shadow-sm' => isset($activeCategory) && $activeCategory->id === $category->id, 'border border-surface-border bg-slate-50 text-ink hover:bg-slate-100' => ! isset($activeCategory) || $activeCategory->id !== $category->id])>{{ $category->name }}</a>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </section>

    <section class="container-site space-y-12 py-10">
        @if ($posts->isEmpty())
            <p class="text-ink-muted">No articles are published yet. Check back soon.</p>
        @else
            @if ($featured)
                <article class="card-hover overflow-hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-12">
                        <div class="relative min-h-[280px] overflow-hidden bg-slate-900 lg:col-span-5">
                            @if ($featured->coverMedia)
                                <img src="{{ $featured->coverMedia->url() }}" alt="{{ $featured->title }}" class="h-full w-full object-cover">
                            @endif
                            <div class="absolute left-4 top-4">
                                <span class="badge bg-brand-blue text-white uppercase tracking-wide">Lead paper</span>
                            </div>
                        </div>
                        <div class="flex flex-col justify-between p-6 sm:p-8 lg:col-span-7 lg:p-10">
                            <div class="space-y-4">
                                <div class="flex flex-wrap items-center gap-3 text-xs text-ink-muted">
                                    @if ($featured->category)
                                        <span class="inline-flex items-center gap-1 font-semibold text-brand-blue">
                                            <x-icon name="verified" class="text-[16px]" />
                                            {{ $featured->category->name }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1">
                                        <x-icon name="schedule" class="text-[16px]" />
                                        {{ $featured->reading_minutes }} min read
                                    </span>
                                    <time datetime="{{ $featured->published_at?->toDateString() }}">{{ $featured->published_at?->format('M Y') }}</time>
                                </div>
                                <h2 class="font-heading text-3xl font-bold text-ink">
                                    <a href="{{ route('blog.show', $featured) }}" class="hover:text-brand-blue">{{ $featured->title }}</a>
                                </h2>
                                <p class="text-ink-muted">{{ $featured->excerpt }}</p>
                            </div>
                            <div class="mt-8 flex items-center justify-between border-t border-surface-border pt-6">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                        {{ strtoupper(substr($featured->author?->name ?? 'EG', 0, 2)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $featured->author?->name ?? 'Ecozeen Group' }}</p>
                                        <p class="text-[11px] text-ink-muted">{{ $featured->published_at?->format('M j, Y') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $featured) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-blue">
                                    Read executive report
                                    <x-icon name="arrow_forward" class="text-[18px]" />
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @endif

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <div class="space-y-6 lg:col-span-8">
                    <div class="flex items-center justify-between border-b border-surface-border pb-3">
                        <h3 class="flex items-center gap-2 font-heading text-xl font-semibold text-ink">
                            <x-icon name="newspaper" class="text-brand-blue" />
                            Corporate dispatches &amp; papers
                        </h3>
                        <span class="text-sm text-ink-muted">Showing {{ $posts->total() }} {{ $posts->total() === 1 ? 'article' : 'articles' }}</span>
                    </div>
                    @if ($grid->isEmpty())
                        <p class="text-sm text-ink-muted">No additional articles in this view.</p>
                    @else
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @foreach ($grid as $post)
                                <article class="card flex flex-col justify-between p-6 hover:border-brand-blue/50">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between text-[11px]">
                                            <span class="badge-info uppercase tracking-wider">{{ $post->category?->name ?? 'Insight' }}</span>
                                            <span class="inline-flex items-center gap-1 text-ink-muted">
                                                <x-icon name="timer" class="text-[14px]" /> {{ $post->reading_minutes }} min read
                                            </span>
                                        </div>
                                        <h4 class="font-heading text-lg font-semibold text-ink">
                                            <a href="{{ route('blog.show', $post) }}" class="hover:text-brand-blue">{{ $post->title }}</a>
                                        </h4>
                                        <p class="line-clamp-3 text-sm text-ink-muted">{{ $post->excerpt }}</p>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between border-t border-surface-border pt-5 text-sm">
                                        <span class="text-ink-muted">{{ $post->published_at?->format('M j, Y') }}</span>
                                        <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center gap-1 font-semibold text-brand-blue">
                                            Read paper <x-icon name="chevron_right" class="text-[16px]" />
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-4">{{ $posts->links() }}</div>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <div class="card p-6">
                        <h3 class="font-heading text-base font-bold text-ink">Browse by category</h3>
                        <div class="mt-4 space-y-2">
                            @forelse ($categories as $category)
                                <a href="{{ route('blog.category', $category) }}" class="flex items-center justify-between rounded-lg px-2 py-2 text-sm hover:bg-slate-50">
                                    <span class="font-medium text-ink">{{ $category->name }}</span>
                                    <x-icon name="chevron_right" class="text-ink-muted text-[16px]" />
                                </a>
                            @empty
                                <p class="text-sm text-ink-muted">Categories will appear as articles are published.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="rounded-xl bg-brand-navy p-8 text-white">
                        <h3 class="font-heading text-lg font-bold">Stay informed</h3>
                        <p class="mt-2 text-sm text-slate-300">Subscribe for occasional updates from across Ecozeen Group.</p>
                        <a href="{{ route('home') }}#inquiry-form" class="btn-secondary mt-5 w-full">Contact the editorial desk</a>
                    </div>
                </aside>
            </div>
        @endif
    </section>
</x-layouts.public>
