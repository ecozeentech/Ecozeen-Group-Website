@php
    $posts = \App\Models\BlogPost::query()->published()
        ->when($settings['featured_only'] ?? false, fn ($q) => $q->featured())
        ->orderByDesc('published_at')
        ->limit($settings['limit'] ?? 3)
        ->get();
@endphp
@if ($posts->isNotEmpty())
    <section class="section-spacing bg-surface-canvas">
        <div class="container-site">
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    @if (! empty($settings['heading']))
                        <h2 class="font-heading text-2xl font-bold text-ink sm:text-3xl">{{ $settings['heading'] }}</h2>
                    @endif
                    @if (! empty($settings['subheading']))
                        <p class="mt-2 text-ink-muted">{{ $settings['subheading'] }}</p>
                    @endif
                </div>
                @if (! empty($settings['cta_label']))
                    <a href="{{ $settings['cta_url'] ?: route('blog.index') }}" class="btn-outline shrink-0">{{ $settings['cta_label'] }}</a>
                @endif
            </div>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <a href="{{ route('blog.show', $post) }}" class="card overflow-hidden transition hover:shadow-level2">
                        @if ($post->coverMedia)
                            <img src="{{ $post->coverMedia->url() }}" alt="{{ $post->title }}" class="h-40 w-full object-cover">
                        @endif
                        <div class="p-5">
                            <p class="text-xs font-medium uppercase tracking-wide text-brand-blue">{{ $post->category?->name }}</p>
                            <h3 class="mt-1 font-heading text-base font-semibold text-ink">{{ $post->title }}</h3>
                            <p class="mt-2 text-sm text-ink-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
