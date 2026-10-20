@php($editing = $redirect->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Redirect'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Redirect'" />

    <form method="POST" action="{{ $editing ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}" class="card max-w-xl space-y-5 p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-admin.input label="From path" name="from_path" :value="$redirect->from_path" placeholder="/old-page" hint="Relative path beginning with a slash." required />
        <x-admin.input label="To path" name="to_path" :value="$redirect->to_path" placeholder="/new-page or https://example.com" required />
        <x-admin.select label="Status code" name="status_code" :value="$redirect->status_code ?? 301" :options="[301 => '301 — Permanent', 302 => '302 — Temporary']" required />
        <x-admin.checkbox label="Active" name="is_active" :checked="$redirect->is_active ?? true" />

        <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
            <a href="{{ route('admin.redirects.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $editing ? 'Save changes' : 'Create redirect' }}</button>
        </div>
    </form>
</x-layouts.admin>
