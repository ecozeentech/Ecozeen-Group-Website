<x-layouts.admin title="Portfolio Projects">
    <x-admin.page-header title="Portfolio Projects" description="Manage case studies and completed engagements.">
        <x-slot:actions>
            @can('projects.create')
                <a href="{{ route('admin.projects.create') }}" class="btn-primary">+ New Project</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search projects..." />

    @if ($projects->isEmpty())
        <x-admin.empty-state title="No projects yet" description="Add your first portfolio project." />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead><tr><th>Title</th><th>Company</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td class="font-medium">{{ $project->title }}</td>
                            <td class="text-ink-muted">{{ $project->company?->name ?? '—' }}</td>
                            <td><x-admin.status-badge :status="$project->status" /></td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('portfolio.show', $project) }}" target="_blank" class="btn-outline">View</a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn-outline">Edit</a>
                                    @can('projects.delete')
                                        <x-admin.delete-button :action="route('admin.projects.destroy', $project)" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $projects->links() }}</div>
    @endif
</x-layouts.admin>
