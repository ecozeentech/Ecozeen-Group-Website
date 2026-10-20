<x-layouts.public seo-title="Insights" seo-description="News, analysis, and perspectives from across Ecozeen Group.">
    <section class="section-spacing">
        <div class="container-site">
            <div class="max-w-2xl">
                <h1 class="font-heading text-3xl font-bold text-ink sm:text-4xl">Insights</h1>
                <p class="mt-3 text-ink-muted">
                    @if (isset($activeCategory)) Category: {{ $activeCategory->name }} @elseif (isset($activeTag)) Tag: {{ $activeTag->name }} @else News and perspectives from across Ecozeen Group. @endif
                </p>
            </div>

            @if ($categories->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('blog.index') }}" class="badge {{ ! isset($activeCategory) ? 'bg-brand-navy text-white' : 'badge-neutral' }}">All</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('blog.category', $category) }}" class="badge {{ (isset($activeCategory) && $activeCategory->id === $category->id) ? 'bg-brand-navy text-white' : 'badge-neutral' }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($posts->isEmpty())
                <p class="mt-10 text-ink-muted">No articles are published yet. Check back soon.</p>
            @else
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <a href="{{ route('blog.show', $post) }}" class="card overflow-hidden transition hover:shadow-level2">
                            @if ($post->coverMedia)
                                <img src="{{ $post->coverMedia->url() }}" alt="{{ $post->title }}" class="h-44 w-full object-cover">
                            @endif
                            <div class="p-5">
                                <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $post->category?->name }}</p>
                                <h2 class="mt-1 font-heading text-base font-semibold text-ink">{{ $post->title }}</h2>
                                <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                                <p class="mt-3 text-xs text-ink-muted">{{ $post->published_at?->format('M j, Y') }} &middot; {{ $post->reading_minutes }} min read</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
