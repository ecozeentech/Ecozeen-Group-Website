<x-layouts.admin title="Business Directory">
    <x-admin.page-header title="Corporate ecosystem &amp; subsidiary directory" description="Manage Ecozeen Group companies, subsidiaries, and business divisions.">
        <x-slot:actions>
            @can('companies.create')
                <a href="{{ route('admin.companies.create') }}" class="btn-secondary">
                    <x-icon name="add_circle" class="text-[18px]" />
                    New company
                </a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="kpi-stripe card p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Registered entities</p>
            <p class="mt-2 font-heading text-2xl font-bold text-ink">{{ $stats['total'] }}</p>
        </div>
        <div class="kpi-stripe card p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Published</p>
            <p class="mt-2 font-heading text-2xl font-bold text-ink">{{ $stats['published'] }}</p>
        </div>
        <div class="kpi-stripe card p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Operating units</p>
            <p class="mt-2 font-heading text-2xl font-bold text-ink">{{ $stats['subsidiaries'] }}</p>
        </div>
        <div class="kpi-stripe card p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted">Featured</p>
            <p class="mt-2 font-heading text-2xl font-bold text-ink">{{ $stats['featured'] }}</p>
        </div>
    </section>

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
            <div class="flex items-center justify-between border-b border-surface-border px-4 py-3">
                <h2 class="font-heading text-sm font-bold text-ink">Registered subsidiaries &amp; assets</h2>
                <span class="text-xs text-ink-muted">{{ $companies->total() }} records</span>
            </div>
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Entity name &amp; wordmark</th>
                        <th>Category / sector</th>
                        <th>Regional jurisdiction</th>
                        <th>Associated platforms</th>
                        <th>Status</th>
                        <th class="text-right">Quick actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $company)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if ($company->logoMedia)
                                        <img src="{{ $company->logoMedia->url() }}" alt="" class="h-8 w-8 rounded object-contain">
                                    @else
                                        <span class="flex h-8 w-8 items-center justify-center rounded bg-slate-100 text-[10px] font-bold text-ink">{{ strtoupper(substr($company->name, 0, 2)) }}</span>
                                    @endif
                                    <div>
                                        <p class="font-medium">{{ $company->name }}</p>
                                        @if ($company->parentCompany)
                                            <p class="text-xs text-ink-muted">Under {{ $company->parentCompany->name }}</p>
                                        @else
                                            <p class="text-xs text-ink-muted">{{ \Illuminate\Support\Str::headline($company->type) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-ink-muted">{{ $company->category ?: '—' }}</td>
                            <td class="text-ink-muted">{{ $company->region ?: '—' }}</td>
                            <td class="text-ink-muted">{{ $company->products_count }} products · {{ $company->services_count }} services</td>
                            <td><x-admin.status-badge :status="$company->status" /></td>
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
