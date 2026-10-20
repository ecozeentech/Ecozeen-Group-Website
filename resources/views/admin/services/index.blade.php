<x-layouts.admin title="Services">
    <x-admin.page-header title="Services" description="Manage enterprise services offered across the group.">
        <x-slot:actions>
            @can('services.create')
                <a href="{{ route('admin.services.create') }}" class="btn-primary">+ New Service</a>
            @endcan
            <a href="{{ route('admin.service-categories.index') }}" class="btn-outline">Categories</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search services..." />

    @if ($services->isEmpty())
        <x-admin.empty-state title="No services yet" description="Add your first service offering." />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead><tr><th>Name</th><th>Category</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td class="font-medium">{{ $service->name }}</td>
                            <td class="text-ink-muted">{{ $service->category?->name ?? '—' }}</td>
                            <td><x-admin.status-badge :status="$service->status" /></td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('services.show', $service) }}" target="_blank" class="btn-outline">View</a>
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn-outline">Edit</a>
                                    @can('services.delete')
                                        <x-admin.delete-button :action="route('admin.services.destroy', $service)" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $services->links() }}</div>
    @endif
</x-layouts.admin>
