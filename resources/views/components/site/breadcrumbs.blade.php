@props(['items' => []])
@if (! empty($items))
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs font-medium text-ink-muted">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <x-icon name="chevron_right" class="text-[14px] text-slate-400" />
            @endif
            @if (! empty($item['url']) && $index < count($items) - 1)
                <a href="{{ $item['url'] }}" class="hover:text-brand-blue">{{ $item['label'] }}</a>
            @else
                <span class="font-semibold text-ink">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
