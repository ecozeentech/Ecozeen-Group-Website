<x-layouts.admin title="Redirects">
    <x-admin.page-header title="Redirects" description="301/302 URL redirects, evaluated before routing on every request.">
        <x-slot:actions>
            <a href="{{ route('admin.redirects.create') }}" class="btn-primary">+ New Redirect</a>
        </x-slot:actions>
    </x-admin.page-header>

    @if ($redirects->isEmpty())
        <x-admin.empty-state title="No redirects configured" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Code</th>
                        <th>Active</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($redirects as $redirect)
                        <tr>
                            <td class="font-mono text-sm">{{ $redirect->from_path }}</td>
                            <td class="font-mono text-sm text-ink-muted">{{ $redirect->to_path }}</td>
                            <td class="text-ink-muted">{{ $redirect->status_code }}</td>
                            <td>
                                @if ($redirect->is_active)
                                    <span class="admin-badge admin-badge-success">Active</span>
                                @else
                                    <span class="admin-badge admin-badge-neutral">Disabled</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.redirects.edit', $redirect) }}" class="btn-outline">Edit</a>
                                    <x-admin.delete-button :action="route('admin.redirects.destroy', $redirect)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $redirects->links() }}</div>
    @endif
</x-layouts.admin>
