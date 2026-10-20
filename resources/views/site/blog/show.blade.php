<x-layouts.public
    :seo-title="$post->seoTitle()"
    :seo-description="$post->seoDescription()"
    :seo-image="$post->seoImage()?->url()"
    :no-index="$post->no_index"
    seo-type="article"
    :structured-data="[
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'datePublished' => optional($post->published_at)->toAtomString(),
        'author' => ['@type' => 'Person', 'name' => $post->author?->name],
    ]"
>
    <article class="section-spacing">
        <div class="container-site max-w-3xl">
            <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $post->category?->name }}</p>
            <h1 class="mt-2 font-heading text-3xl font-bold text-ink sm:text-4xl">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-ink-muted">
                By {{ $post->author?->name ?? 'Ecozeen Group' }} &middot; {{ $post->published_at?->format('F j, Y') }} &middot; {{ $post->reading_minutes }} min read
            </p>

            @if ($post->coverMedia)
                <img src="{{ $post->coverMedia->url() }}" alt="{{ $post->title }}" class="mt-6 w-full rounded-xl shadow-level2">
            @endif

            <div class="prose prose-slate mt-8 max-w-none">{!! $post->body !!}</div>

            @if ($post->tags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" class="badge badge-neutral">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="section-spacing bg-surface-canvas">
            <div class="container-site">
                <h2 class="font-heading text-xl font-bold text-ink">More Insights</h2>
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ route('blog.show', $item) }}" class="card p-5 transition hover:shadow-level2">
                            <h3 class="font-heading text-sm font-semibold text-ink">{{ $item->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.public>
