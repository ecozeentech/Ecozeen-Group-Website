<x-layouts.admin title="New Navigation Menu">
    <x-admin.page-header title="New Navigation Menu" />

    <form method="POST" action="{{ route('admin.navigation.store') }}" class="card max-w-xl space-y-5 p-6">
        @csrf
        <x-admin.input label="Name" name="name" required />
        <x-admin.input label="Location" name="location" required hint="Use 'main' for the header menu or 'footer' for the footer. The public layout looks these up exactly." />
        <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
            <a href="{{ route('admin.navigation.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">Create &amp; Add Items</button>
        </div>
    </form>
</x-layouts.admin>
