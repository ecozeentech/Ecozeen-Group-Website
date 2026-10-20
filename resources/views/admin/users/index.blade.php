<x-layouts.admin title="Users">
    <x-admin.page-header title="Users" description="Manage administrator and staff accounts.">
        <x-slot:actions>
            @can('create', \App\Models\User::class)
                <a href="{{ route('admin.users.create') }}" class="btn-primary">+ New User</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search name or email..." />

    @if ($users->isEmpty())
        <x-admin.empty-state title="No users found" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="font-medium">{{ $user->name }}</td>
                            <td class="text-ink-muted">{{ $user->email }}</td>
                            <td class="text-ink-muted">{{ $user->roles->pluck('name')->join(', ') ?: '—' }}</td>
                            <td>
                                @if ($user->is_active)
                                    <span class="admin-badge admin-badge-success">Active</span>
                                @else
                                    <span class="admin-badge admin-badge-neutral">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    @can('update', $user)
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline">Edit</a>
                                    @endcan
                                    @if ($user->id !== auth()->id())
                                        <x-admin.delete-button :action="route('admin.users.destroy', $user)" confirm="Deactivate this user account?" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    @endif
</x-layouts.admin>
