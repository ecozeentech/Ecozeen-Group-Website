@php($editing = $role->exists)
@php($locked = $editing && $role->name === 'Super Administrator')
@php($grouped = $permissions->groupBy(fn ($permission) => str($permission->name)->before('.')->headline()))
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Role'">
    <x-admin.page-header :title="$editing ? $role->name : 'New Role'" />

    @if ($locked)
        <div class="mb-6 rounded-token border border-amber-300 bg-amber-50 p-4 text-sm text-amber-800">
            The Super Administrator role always has full access and cannot be modified or deleted.
        </div>
    @endif

    <form method="POST" action="{{ $editing ? route('admin.roles.update', $role) : route('admin.roles.store') }}" class="card space-y-6 p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        @if (! $editing)
            <x-admin.input label="Role name" name="name" required />
        @endif

        <div>
            <h2 class="font-heading text-sm font-semibold text-ink">Permissions</h2>
            <div class="mt-3 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($grouped as $group => $items)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">{{ $group }}</p>
                        <div class="mt-2 space-y-1.5">
                            @foreach ($items as $permission)
                                <label class="flex items-center gap-2 text-sm text-ink">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked(in_array($permission->name, $assigned, true)) @disabled($locked) class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue/30">
                                    {{ $permission->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @unless ($locked)
            <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
                <a href="{{ route('admin.roles.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">{{ $editing ? 'Save permissions' : 'Create role' }}</button>
            </div>
        @endunless
    </form>
</x-layouts.admin>
