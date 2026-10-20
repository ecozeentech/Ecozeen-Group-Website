<x-layouts.admin :title="$title">
    <x-admin.page-header :title="$title">
        <x-slot:actions>
            <a href="{{ route('admin.'.$routeName.'.create') }}" class="btn-primary">+ New</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search by name..." />

    @if ($items->isEmpty())
        <x-admin.empty-state title="No records yet" description="Create your first entry to get started.">
            <x-slot:action>
                <a href="{{ route('admin.'.$routeName.'.create') }}" class="btn-primary">+ New</a>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        @if ($hasDescription)
                            <th>Description</th>
                        @endif
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="font-medium">{{ $item->name }}</td>
                            <td class="text-ink-muted">{{ $item->slug }}</td>
                            @if ($hasDescription)
                                <td class="text-ink-muted">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</td>
                            @endif
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.'.$routeName.'.edit', $item) }}" class="btn-outline">Edit</a>
                                    <x-admin.delete-button :action="route('admin.'.$routeName.'.destroy', $item)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $items->links() }}</div>
    @endif
</x-layouts.admin>
