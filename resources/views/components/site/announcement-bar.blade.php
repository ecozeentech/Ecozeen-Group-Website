@props(['settings'])
@if ($settings->announcement_enabled && $settings->announcement_text)
    <aside
        x-data="{ open: true }"
        x-show="open"
        class="z-50 w-full border-b border-white/10 bg-brand-navy px-4 py-2.5 text-white"
        id="announcement-bar"
    >
        <div class="container-site flex items-center justify-between gap-3">
            <div class="flex min-w-0 flex-1 items-center justify-center gap-3">
                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-950 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wider text-emerald-300">
                    <span class="h-1.5 w-1.5 animate-ping rounded-full bg-emerald-300"></span>
                    Notice
                </span>
                <p class="truncate text-xs sm:text-sm">{{ $settings->announcement_text }}</p>
            </div>
            <button type="button" class="rounded-md p-1 text-white/80 transition hover:bg-white/10 hover:text-white" @click="open = false" aria-label="Dismiss notice">
                <x-icon name="close" class="text-[18px]" />
            </button>
        </div>
    </aside>
@endif
