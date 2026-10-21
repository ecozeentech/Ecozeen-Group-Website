<x-layouts.admin :title="'Navigation — '.$menu->name">
    <x-admin.page-header :title="$menu->name" :description="$menu->location === 'main' ? 'This menu is the public header. Edit labels, links, order, and dropdowns here.' : ($menu->location === 'footer' ? 'Top-level items become footer columns. Child items become the links under each heading.' : 'Location: '.$menu->location)">
        <x-slot:actions>
            @can('settings.manage')
                <a href="{{ route('admin.settings.website.edit') }}" class="btn-outline">Header buttons &amp; footer copy</a>
            @endcan
            <a href="{{ route('admin.navigation.index') }}" class="btn-outline">Back to Menus</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
        Changes appear on the live site immediately. Use <strong>Edit</strong> to rename or retarget a link, arrows to reorder, and <strong>Delete</strong> to remove it.
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-3">
            @if ($topLevelItems->isEmpty())
                <div class="card p-6">
                    <p class="text-sm text-ink-muted">No items yet. Add the first link using the form on the right.</p>
                </div>
            @else
                @foreach ($topLevelItems->sortBy('sort_order')->values() as $item)
                    @include('admin.navigation._item', [
                        'item' => $item,
                        'menu' => $menu,
                        'pages' => $pages,
                        'topLevelItems' => $topLevelItems,
                        'siblings' => $topLevelItems->sortBy('sort_order')->values(),
                        'depth' => 0,
                        'openDefault' => $loop->first,
                    ])
                @endforeach
            @endif
        </div>

        <div class="card h-fit p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Add item</h2>
            <p class="mt-1 text-xs text-ink-muted">Leave parent empty for a top-level item. For the footer, top-level items are column headings.</p>
            <form method="POST" action="{{ route('admin.navigation.items.store', $menu) }}" class="mt-4 space-y-4">
                @csrf
                <x-admin.input label="Label" name="label" required />
                <x-admin.select label="Parent item (optional)" name="parent_id" placeholder="— Top level —" :options="$topLevelItems->pluck('label', 'id')" />
                <x-admin.select label="Link to a page" name="page_id" placeholder="— None —" :options="$pages->pluck('title', 'id')" />
                <x-admin.input label="Or a custom URL" name="url" hint="Used only if no page is selected, e.g. /about or https://ecozeenswap.com" />
                <x-admin.checkbox label="Open in new tab" name="open_in_new_tab" />
                <x-admin.checkbox label="Visible" name="is_visible" :checked="true" />
                <x-admin.input type="number" label="Sort order" name="sort_order" />
                <button type="submit" class="btn-primary w-full">Add item</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
