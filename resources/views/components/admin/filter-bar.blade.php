@props(['placeholder' => 'Search...'])
<form method="GET" class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
    <input
        type="search"
        name="q"
        value="{{ request('q') }}"
        placeholder="{{ $placeholder }}"
        class="form-input sm:max-w-xs"
    >
    {{ $slot }}
    <button type="submit" class="btn-outline">Filter</button>
    @if (request()->query())
        <a href="{{ url()->current() }}" class="text-sm font-medium text-ink-muted hover:text-brand-blue">Reset</a>
    @endif
</form>
