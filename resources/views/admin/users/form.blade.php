@php($editing = $user->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' User'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' User'" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}" class="card space-y-5 p-6 lg:col-span-2">
            @csrf
            @if ($editing) @method('PUT') @endif

            <x-admin.input label="Full name" name="name" :value="$user->name" required />
            <x-admin.input type="email" label="Email" name="email" :value="$user->email" required />
            <x-admin.input type="password" label="Password" name="password" :hint="$editing ? 'Leave blank to keep the current password.' : 'Minimum 12 characters.'" :required="! $editing" />
            <x-admin.checkbox label="Active" name="is_active" :checked="$user->is_active ?? true" hint="Inactive accounts cannot sign in." />

            @if (! $editing && auth()->user()->hasRole('Super Administrator'))
                <x-admin.select label="Initial role" name="role" placeholder="— None —" :options="$roles->pluck('name', 'name')" />
            @endif

            <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
                <a href="{{ route('admin.users.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">{{ $editing ? 'Save changes' : 'Create user' }}</button>
            </div>
        </form>

        @if ($editing && auth()->user()->can('manageRoles', $user))
            <form method="POST" action="{{ route('admin.users.roles.update', $user) }}" class="card space-y-3 p-6">
                @csrf
                @method('PUT')
                <h2 class="font-heading text-sm font-semibold text-ink">Roles</h2>
                <p class="text-xs text-ink-muted">Changing roles takes effect immediately and is recorded in the activity log.</p>
                <div class="space-y-2">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-2 text-sm text-ink">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked($user->roles->pluck('name')->contains($role->name)) class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue/30">
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn-primary w-full">Update Roles</button>
            </form>
        @endif
    </div>
</x-layouts.admin>
