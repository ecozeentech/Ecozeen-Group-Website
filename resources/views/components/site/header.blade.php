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
    class="sticky top-0 z-50 border-b border-surface-border/80 bg-white/90 shadow-sm backdrop-blur-md"
>
    <div class="container-site flex h-20 items-center justify-between">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
            <x-brand.logo :settings="$settings" />
        </a>

        <nav class="hidden items-center gap-8 lg:flex" aria-label="Primary navigation">
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
                                <x-icon name="expand_more" class="text-[16px]" />
                            @endif
                        </a>
                        @if ($item->children->isNotEmpty())
                            <div
                                x-show="open"
                                x-transition
                                x-cloak
                                class="absolute left-0 top-full z-50 mt-2 w-56 rounded-xl border border-surface-border bg-white py-2 shadow-level2"
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
                <x-icon name="arrow_forward" class="text-[18px]" />
            </a>
        </div>

        <button
            type="button"
            class="inline-flex items-center justify-center rounded-token p-2 text-ink lg:hidden"
            @click="mobileOpen = !mobileOpen"
            :aria-expanded="mobileOpen"
            aria-label="Toggle navigation menu"
        >
            <x-icon name="menu" x-show="!mobileOpen" />
            <span x-show="mobileOpen" x-cloak><x-icon name="close" /></span>
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
