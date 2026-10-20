@php($editing = $item->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' — '.$title">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' '.$title" />

    <form method="POST" action="{{ $editing ? route('admin.'.$routeName.'.update', $item) : route('admin.'.$routeName.'.store') }}" class="card max-w-2xl space-y-5 p-6">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <x-admin.input label="Name" name="name" :value="$item->name" required />
        <x-admin.input label="Slug" name="slug" :value="$item->slug" hint="Leave blank to auto-generate from the name." />

        @if ($hasDescription ?? true)
            <x-admin.textarea label="Description" name="description" :value="$item->description" />
        @endif

        @if ($hasSortOrder ?? true)
            <x-admin.input type="number" label="Sort order" name="sort_order" :value="$item->sort_order ?? 0" hint="Lower numbers appear first." />
        @endif

        <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
            <a href="{{ route('admin.'.$routeName.'.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $editing ? 'Save changes' : 'Create' }}</button>
        </div>
    </form>
</x-layouts.admin>
