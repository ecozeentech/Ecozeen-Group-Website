@props(['title' => 'Nothing here yet', 'description' => null])
<div class="card flex flex-col items-center justify-center px-6 py-16 text-center">
    <svg class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7.5l9-4.5 9 4.5m-18 0l9 4.5m-9-4.5v9l9 4.5m0-13.5l9-4.5m-9 4.5v9m9-13.5v9l-9 4.5" /></svg>
    <p class="mt-4 font-heading text-sm font-semibold text-ink">{{ $title }}</p>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-ink-muted">{{ $description }}</p>
    @endif
    @if (isset($action))
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
