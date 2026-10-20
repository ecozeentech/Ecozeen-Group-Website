<x-layouts.admin title="Activity Log">
    <x-admin.page-header title="Activity Log" description="Audit trail of create/update/delete actions taken across the admin panel." />

    <x-admin.filter-bar placeholder="">
        <select name="action" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All actions</option>
            @foreach (['created', 'updated', 'deleted', 'published', 'unpublished', 'restored'] as $action)
                <option value="{{ $action }}" @selected(request('action') === $action)>{{ \Illuminate\Support\Str::headline($action) }}</option>
            @endforeach
        </select>
    </x-admin.filter-bar>

    @if ($logs->isEmpty())
        <x-admin.empty-state title="No activity recorded yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap text-ink-muted">{{ $log->created_at->format('M j, Y H:i') }}</td>
                            <td class="font-medium">{{ $log->user?->name ?? 'System' }}</td>
                            <td><x-admin.status-badge :status="$log->action" /></td>
                            <td class="text-ink-muted">{{ $log->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
    @endif
</x-layouts.admin>
