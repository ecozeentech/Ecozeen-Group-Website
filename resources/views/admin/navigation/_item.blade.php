@php
    $siblings = $siblings ?? collect();
    $index = $siblings->search(fn ($candidate) => $candidate->id === $item->id);
    $parentOptions = $topLevelItems
        ->reject(fn ($candidate) => $candidate->id === $item->id)
        ->pluck('label', 'id');
    $preview = $item->resolvedUrl();
@endphp
<div class="rounded-token border border-surface-border {{ $depth ? 'bg-slate-50' : 'bg-white' }}" id="nav-item-{{ $item->id }}" x-data="{ open: {{ $openDefault ? 'true' : 'false' }} }">
    <div class="flex flex-wrap items-center justify-between gap-3 p-4">
        <button type="button" class="min-w-0 flex-1 text-left" @click="open = !open">
            <p class="font-medium text-ink">{{ $item->label }}</p>
            <p class="truncate text-xs text-ink-muted">{{ $preview }}</p>
        </button>
        <div class="flex flex-wrap items-center gap-2">
            @unless ($item->is_visible)
                <span class="badge badge-neutral">Hidden</span>
            @endunless
            @if ($index !== false && $index > 0)
                @php($swapped = $siblings->pluck('id')->values()->all())
                @php([$swapped[$index - 1], $swapped[$index]] = [$swapped[$index], $swapped[$index - 1]])
                <form method="POST" action="{{ route('admin.navigation.items.reorder', $menu) }}">
                    @csrf
                    @foreach ($swapped as $id)
                        <input type="hidden" name="order[]" value="{{ $id }}">
                    @endforeach
                    <button type="submit" class="btn-outline" aria-label="Move up">&uarr;</button>
                </form>
            @endif
            @if ($index !== false && $index < $siblings->count() - 1)
                @php($swapped = $siblings->pluck('id')->values()->all())
                @php([$swapped[$index + 1], $swapped[$index]] = [$swapped[$index], $swapped[$index + 1]])
                <form method="POST" action="{{ route('admin.navigation.items.reorder', $menu) }}">
                    @csrf
                    @foreach ($swapped as $id)
                        <input type="hidden" name="order[]" value="{{ $id }}">
                    @endforeach
                    <button type="submit" class="btn-outline" aria-label="Move down">&darr;</button>
                </form>
            @endif
            <button type="button" class="btn-outline" @click="open = !open">
                <span x-text="open ? 'Close' : 'Edit'"></span>
            </button>
            <x-admin.delete-button :action="route('admin.navigation.items.destroy', [$menu, $item])" label="Delete" :confirm="$depth ? 'Delete this link?' : 'Delete this item and its child links?'" />
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-surface-border p-4">
        <form method="POST" action="{{ route('admin.navigation.items.update', [$menu, $item]) }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @csrf
            @method('PUT')
            <x-admin.input label="Label" name="label" :value="$item->label" required />
            <x-admin.input label="Custom URL" name="url" :value="$item->url" hint="Used when no page is selected. Examples: /about or https://ecozeenswap.com" />
            <x-admin.select label="Link to a page" name="page_id" :value="$item->page_id" placeholder="— None —" :options="$pages->pluck('title', 'id')" />
            <x-admin.select label="Parent item" name="parent_id" :value="$item->parent_id" placeholder="— Top level —" :options="$parentOptions" />
            <x-admin.input type="number" label="Sort order" name="sort_order" :value="$item->sort_order" />
            <div class="flex flex-col justify-end gap-3">
                <x-admin.checkbox label="Open in new tab" name="open_in_new_tab" :checked="$item->open_in_new_tab" />
                <x-admin.checkbox label="Visible" name="is_visible" :checked="$item->is_visible" />
            </div>
            <div class="sm:col-span-2 flex justify-end">
                <button type="submit" class="btn-primary">Save item</button>
            </div>
        </form>
    </div>

    @if ($item->children->isNotEmpty())
        <div class="space-y-3 border-t border-surface-border p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Child links</p>
            @foreach ($item->children->sortBy('sort_order') as $child)
                @include('admin.navigation._item', [
                    'item' => $child,
                    'menu' => $menu,
                    'pages' => $pages,
                    'topLevelItems' => $topLevelItems,
                    'siblings' => $item->children->sortBy('sort_order')->values(),
                    'depth' => 1,
                    'openDefault' => false,
                ])
            @endforeach
        </div>
    @endif
</div>
