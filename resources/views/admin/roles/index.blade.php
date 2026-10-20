<x-layouts.admin title="Roles & Permissions">
    <x-admin.page-header title="Roles & Permissions" description="Only Super Administrators may create roles or change permission grants.">
        <x-slot:actions>
            @if (auth()->user()->hasRole('Super Administrator'))
                <a href="{{ route('admin.roles.create') }}" class="btn-primary">+ New Role</a>
            @endif
        </x-slot:actions>
    </x-admin.page-header>

    <div class="card overflow-x-auto">
        <table class="admin-table w-full">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Users</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td class="font-medium">{{ $role->name }}</td>
                        <td class="text-ink-muted">{{ $role->users_count }}</td>
                        <td class="text-right">
                            <div class="inline-flex gap-2">
                                @if (auth()->user()->hasRole('Super Administrator'))
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn-outline">{{ $role->name === 'Super Administrator' ? 'View' : 'Edit' }}</a>
                                    @if ($role->name !== 'Super Administrator' && $role->users_count === 0)
                                        <x-admin.delete-button :action="route('admin.roles.destroy', $role)" />
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
