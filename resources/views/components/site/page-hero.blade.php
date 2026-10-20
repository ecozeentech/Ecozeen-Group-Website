@props(['eyebrow' => null, 'title', 'description' => null])
<section class="border-b border-surface-border bg-white">
    <div class="container-site py-12 sm:py-16">
        @if ($eyebrow)
            <p class="text-eyebrow">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-emerald" aria-hidden="true"></span>
                {{ $eyebrow }}
            </p>
        @endif
        <h1 class="mt-3 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-ink-muted">{{ $description }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
