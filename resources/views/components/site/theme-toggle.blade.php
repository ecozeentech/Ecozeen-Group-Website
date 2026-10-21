<button
    type="button"
    {{ $attributes->merge(['class' => 'inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-surface-border bg-surface text-ink transition hover:bg-slate-50 dark:hover:bg-slate-800']) }}
    onclick="window.ecozeenTheme && window.ecozeenTheme.toggle()"
    aria-label="Switch between light and dark mode"
>
    <span class="dark:hidden" title="Dark mode">
        <x-icon name="dark_mode" class="text-[20px]" />
    </span>
    <span class="hidden dark:inline" title="Light mode">
        <x-icon name="light_mode" class="text-[20px]" />
    </span>
</button>
