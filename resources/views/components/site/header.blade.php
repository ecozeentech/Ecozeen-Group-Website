@props(['settings'])
@php
    $mainMenu = \App\Services\NavigationService::menu('main');
    $partnerPage = \App\Models\Page::query()
        ->where('slug', 'partner')
        ->where('status', 'published')
        ->first();
    $partnerUrl = $partnerPage ? url('/partner') : route('contact');
@endphp
<header
    x-data="{ mobileOpen: false }"
    class="sticky top-0 z-50 border-b border-surface-border bg-white/85 shadow-sm backdrop-blur-md"
>
    <div class="container-site flex h-16 items-center justify-between lg:h-20">
        <a href="{{ route('home') }}" class="shrink-0">
            <x-brand.logo :settings="$settings" class="[&_img]:h-9 [&_img]:sm:h-10" />
        </a>

        <nav class="hidden items-center gap-7 lg:flex" aria-label="Primary navigation">
            @if ($mainMenu)
                @foreach ($mainMenu->items as $item)
                    @php
                        $path = trim($item->resolvedUrl(), '/');
                        $active = $path !== '' && (request()->is($path) || request()->is($path.'/*'));
                    @endphp
                    <div class="relative" x-data="{ open: false }" @mouseleave="open = false">
                        <a
                            href="{{ $item->resolvedUrl() }}"
                            @if ($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                            @if ($item->children->isNotEmpty()) @mouseenter="open = true" @click="open = !open" @endif
                            class="border-b-2 py-1 text-sm font-semibold transition-colors {{ $active ? 'border-brand-blue text-brand-blue' : 'border-transparent text-slate-600 hover:text-ink' }}"
                        >
                            {{ $item->label }}
                            @if ($item->children->isNotEmpty())
                                <svg class="ml-0.5 inline h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" /></svg>
                            @endif
                        </a>
                        @if ($item->children->isNotEmpty())
                            <div
                                x-show="open"
                                x-transition
                                x-cloak
                                class="absolute left-0 top-full z-50 mt-2 w-56 rounded-lg border border-surface-border bg-white py-2 shadow-level2"
                            >
                                @foreach ($item->children as $child)
                                    <a href="{{ $child->resolvedUrl() }}" class="block px-4 py-2 text-sm text-ink hover:bg-slate-50 hover:text-brand-blue">
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('contact') }}" class="btn-outline rounded-xl px-4 py-2">Contact</a>
            <a href="{{ $partnerUrl }}" class="btn-secondary rounded-xl">
                Partner With Us
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.69l-3.22-3.22a.75.75 0 111.06-1.06l4.5 4.5a.75.75 0 010 1.06l-4.5 4.5a.75.75 0 11-1.06-1.06l3.22-3.22H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
            </a>
        </div>

        <button
            type="button"
            class="inline-flex items-center justify-center rounded-token p-2 text-ink lg:hidden"
            @click="mobileOpen = !mobileOpen"
            :aria-expanded="mobileOpen"
            aria-label="Toggle navigation menu"
        >
            <svg x-show="!mobileOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
            <svg x-show="mobileOpen" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <div
        x-show="mobileOpen"
        x-cloak
        x-transition
        class="border-t border-surface-border bg-white lg:hidden"
        role="dialog"
        aria-label="Mobile navigation"
    >
        <nav class="container-site flex flex-col gap-1 py-4" aria-label="Mobile primary navigation">
            @if ($mainMenu)
                @foreach ($mainMenu->items as $item)
                    <a href="{{ $item->resolvedUrl() }}" class="rounded-token px-3 py-2.5 text-base font-medium text-ink hover:bg-slate-50">
                        {{ $item->label }}
                    </a>
                    @foreach ($item->children as $child)
                        <a href="{{ $child->resolvedUrl() }}" class="ml-4 rounded-token px-3 py-2 text-sm text-ink-muted hover:bg-slate-50">
                            {{ $child->label }}
                        </a>
                    @endforeach
                @endforeach
            @endif
            <a href="{{ route('contact') }}" class="btn-outline mt-2 w-full">Contact</a>
            <a href="{{ $partnerUrl }}" class="btn-secondary w-full">Partner With Us</a>
        </nav>
    </div>
</header>
