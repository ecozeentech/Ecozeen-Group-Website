<x-layouts.admin title="Companies">
    <x-admin.page-header title="Companies & Subsidiaries" description="Manage Ecozeen Group's companies, subsidiaries, and business divisions.">
        <x-slot:actions>
            @can('companies.create')
                <a href="{{ route('admin.companies.create') }}" class="btn-primary">+ New Company</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search companies...">
        <select name="status" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (['draft', 'published', 'archived'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </x-admin.filter-bar>

    @if ($companies->isEmpty())
        <x-admin.empty-state title="No companies yet" description="Add the first Ecozeen Group company or subsidiary.">
            <x-slot:action>
                <a href="{{ route('admin.companies.create') }}" class="btn-primary">+ New Company</a>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Region</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td class="font-medium">
                                {{ $company->name }}
                                @if ($company->parentCompany)
                                    <span class="block text-xs text-ink-muted">Under {{ $company->parentCompany->name }}</span>
                                @endif
                            </td>
                            <td class="text-ink-muted">{{ \Illuminate\Support\Str::headline($company->type) }}</td>
                            <td class="text-ink-muted">{{ $company->region }}</td>
                            <td><x-admin.status-badge :status="$company->status" /></td>
                            <td>{{ $company->is_featured ? 'Yes' : 'No' }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('companies.show', $company) }}" target="_blank" class="btn-outline">View</a>
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn-outline">Edit</a>
                                    @can('companies.delete')
                                        <x-admin.delete-button :action="route('admin.companies.destroy', $company)" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $companies->links() }}</div>
    @endif
</x-layouts.admin>
