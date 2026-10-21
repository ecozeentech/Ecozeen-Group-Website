@props(['settings'])
@php
    $mainMenu = \App\Services\NavigationService::menu('main');
    $partnerPage = \App\Models\Page::query()
        ->where('slug', 'partner')
        ->where('status', 'published')
        ->first();
    $defaultPartnerUrl = $partnerPage ? url('/partner') : route('contact');
    $ctaLabel = $settings->header_cta_label ?: 'Partner With Us';
    $ctaUrl = $settings->header_cta_url ?: $defaultPartnerUrl;
    $secondaryLabel = $settings->header_secondary_label ?: 'Contact';
    $secondaryUrl = $settings->header_secondary_url ?: route('contact');
@endphp
<header
    x-data="{ mobileOpen: false }"
    class="sticky top-0 z-50 border-b border-surface-border/80 bg-surface/90 shadow-sm backdrop-blur-md"
>
    <div class="container-site flex h-16 items-center justify-between gap-4 md:h-20">
        <a href="{{ route('home') }}" class="flex min-w-0 shrink-0 items-center gap-3">
            <x-brand.logo :settings="$settings" class="site-header-logo" />
        </a>

        <nav class="hidden min-w-0 flex-1 items-center justify-center gap-6 2xl:gap-8 xl:flex" aria-label="Primary navigation">
            @if ($mainMenu)
                @foreach ($mainMenu->items as $item)
                    @php
                        $path = trim(parse_url($item->resolvedUrl(), PHP_URL_PATH) ?: $item->resolvedUrl(), '/');
                        $active = $path !== '' && (request()->is($path) || request()->is($path.'/*'));
                    @endphp
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <a
                            href="{{ $item->resolvedUrl() }}"
                            @if ($item->open_in_new_tab) target="_blank" rel="noopener" @endif
                            class="whitespace-nowrap text-sm font-medium transition-colors {{ $active ? 'text-brand-blue' : 'text-ink-muted hover:text-ink' }}"
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
                                class="absolute left-1/2 top-full z-50 mt-3 w-56 -translate-x-1/2 rounded-xl border border-surface-border bg-surface py-2 shadow-level2"
                            >
                                @foreach ($item->children as $child)
                                    <a href="{{ $child->resolvedUrl() }}" class="block px-4 py-2 text-sm text-ink hover:bg-slate-50 hover:text-brand-blue dark:hover:bg-slate-800">
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </nav>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <x-site.theme-toggle />

            <div class="hidden items-center gap-3 xl:flex">
                <a href="{{ $secondaryUrl }}" class="btn-outline rounded-xl px-4 py-2">{{ $secondaryLabel }}</a>
                <a href="{{ $ctaUrl }}" class="btn-secondary rounded-xl">
                    {{ $ctaLabel }}
                    <x-icon name="arrow_forward" class="text-[18px]" />
                </a>
            </div>

            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-surface-border text-ink xl:hidden"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen"
                aria-label="Toggle navigation menu"
            >
                <x-icon name="menu" x-show="!mobileOpen" />
                <span x-show="mobileOpen" x-cloak><x-icon name="close" /></span>
            </button>
        </div>
    </div>

    <div
        x-show="mobileOpen"
        x-cloak
        x-transition
        class="max-h-[calc(100vh-4rem)] overflow-y-auto border-t border-surface-border bg-surface xl:hidden"
        role="dialog"
        aria-label="Mobile navigation"
    >
        <nav class="container-site flex flex-col gap-1 py-4" aria-label="Mobile primary navigation">
            @if ($mainMenu)
                @foreach ($mainMenu->items as $item)
                    <a href="{{ $item->resolvedUrl() }}" class="rounded-token px-3 py-2.5 text-base font-medium text-ink hover:bg-slate-50 dark:hover:bg-slate-800">
                        {{ $item->label }}
                    </a>
                    @foreach ($item->children as $child)
                        <a href="{{ $child->resolvedUrl() }}" class="ml-4 rounded-token px-3 py-2 text-sm text-ink-muted hover:bg-slate-50 dark:hover:bg-slate-800">
                            {{ $child->label }}
                        </a>
                    @endforeach
                @endforeach
            @endif
            <div class="mt-3 flex flex-col gap-2 border-t border-surface-border pt-3">
                <a href="{{ $secondaryUrl }}" class="btn-outline w-full">{{ $secondaryLabel }}</a>
                <a href="{{ $ctaUrl }}" class="btn-secondary w-full">{{ $ctaLabel }}</a>
            </div>
        </nav>
    </div>
</header>
