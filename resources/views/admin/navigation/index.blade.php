<x-layouts.admin title="Navigation Menus">
    <x-admin.page-header title="Navigation Menus" description="Edit the public header links and footer columns. Header button labels live in Website Settings.">
        <x-slot:actions>
            <a href="{{ route('admin.navigation.create') }}" class="btn-primary">+ New Menu</a>
        </x-slot:actions>
    </x-admin.page-header>

    @if ($menus->isEmpty())
        <x-admin.empty-state title="No menus yet" description="Create a menu with location 'main' or 'footer' to populate the public site header/footer." />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Items</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr>
                            <td class="font-medium">{{ $menu->name }}</td>
                            <td class="text-ink-muted">{{ $menu->location }}</td>
                            <td class="text-ink-muted">{{ $menu->all_items_count }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.navigation.edit', $menu) }}" class="btn-outline">Edit items</a>
                                    <x-admin.delete-button :action="route('admin.navigation.destroy', $menu)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.admin>
