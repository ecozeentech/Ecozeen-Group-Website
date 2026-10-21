@props(['eyebrow' => null, 'title', 'description' => null, 'crumbs' => []])
<section class="relative overflow-hidden border-b border-surface-border bg-white">
    <div class="container-site py-10 sm:py-14">
        @if (! empty($crumbs))
            <div class="mb-6">
                <x-site.breadcrumbs :items="$crumbs" />
            </div>
        @endif
        @if ($eyebrow)
            <p class="text-eyebrow">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-blue" aria-hidden="true"></span>
                {{ $eyebrow }}
            </p>
        @endif
        <h1 class="mt-3 max-w-4xl font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-ink-muted">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
