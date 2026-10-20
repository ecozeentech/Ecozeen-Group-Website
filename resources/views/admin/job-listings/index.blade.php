<x-layouts.admin title="Job Listings">
    <x-admin.page-header title="Job Listings" description="Manage open roles published to the Careers page.">
        <x-slot:actions>
            @can('jobs.manage')
                <a href="{{ route('admin.job-listings.create') }}" class="btn-primary">+ New Listing</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search listings..." />

    @if ($listings->isEmpty())
        <x-admin.empty-state title="No job listings yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Applications</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listings as $listing)
                        <tr>
                            <td class="font-medium">{{ $listing->title }}</td>
                            <td class="text-ink-muted">{{ $listing->company?->name }}</td>
                            <td><x-admin.status-badge :status="$listing->status" /></td>
                            <td class="text-ink-muted">{{ $listing->applications_count }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    @if ($listing->status === 'open')
                                        <a href="{{ route('careers.show', $listing) }}" target="_blank" class="btn-outline">View</a>
                                    @endif
                                    <a href="{{ route('admin.job-listings.edit', $listing) }}" class="btn-outline">Edit</a>
                                    <x-admin.delete-button :action="route('admin.job-listings.destroy', $listing)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $listings->links() }}</div>
    @endif
</x-layouts.admin>
