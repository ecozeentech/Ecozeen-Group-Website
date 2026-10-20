<x-layouts.admin :title="'Navigation — '.$menu->name">
    <x-admin.page-header :title="$menu->name" :description="'Location: '.$menu->location">
        <x-slot:actions>
            <a href="{{ route('admin.navigation.index') }}" class="btn-outline">Back to Menus</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h2 class="font-heading text-sm font-semibold text-ink">Top-Level Items</h2>
                @if ($topLevelItems->isEmpty())
                    <p class="mt-3 text-sm text-ink-muted">No items yet. Add one using the form below.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($topLevelItems->sortBy('sort_order') as $item)
                            <div class="rounded-token border border-surface-border p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <div>
                                        <p class="font-medium text-ink">{{ $item->label }}</p>
                                        <p class="text-xs text-ink-muted">{{ $item->resolvedUrl() }}</p>
                                    </div>
                                    <x-admin.delete-button :action="route('admin.navigation.items.destroy', [$menu, $item])" label="Remove" confirm="Remove this item (and its children)?" />
                                </div>
                                @if ($item->children->isNotEmpty())
                                    <ul class="mt-3 space-y-2 border-t border-surface-border pt-3">
                                        @foreach ($item->children as $child)
                                            <li class="flex items-center justify-between gap-2 text-sm">
                                                <span>&rsaquo; {{ $child->label }} <span class="text-ink-muted">({{ $child->resolvedUrl() }})</span></span>
                                                <x-admin.delete-button :action="route('admin.navigation.items.destroy', [$menu, $child])" label="Remove" confirm="Remove this item?" />
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Add Item</h2>
            <form method="POST" action="{{ route('admin.navigation.items.store', $menu) }}" class="mt-4 space-y-4">
                @csrf
                <x-admin.input label="Label" name="label" required />
                <x-admin.select label="Parent item (optional)" name="parent_id" placeholder="— Top level —" :options="$topLevelItems->pluck('label', 'id')" />
                <x-admin.select label="Link to a page" name="page_id" placeholder="— None —" :options="$pages->pluck('title', 'id')" />
                <x-admin.input label="Or a custom URL" name="url" hint="Used only if no page is selected, e.g. https://ecozeenswap.com" />
                <x-admin.checkbox label="Open in new tab" name="open_in_new_tab" />
                <x-admin.checkbox label="Visible" name="is_visible" :checked="true" />
                <x-admin.input type="number" label="Sort order" name="sort_order" />
                <button type="submit" class="btn-primary w-full">Add Item</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
