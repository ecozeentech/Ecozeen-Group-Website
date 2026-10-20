<x-layouts.admin title="New Page">
    <x-admin.page-header title="New Page" description="Set up the page basics, then add and configure sections." />

    <form method="POST" action="{{ route('admin.pages.store') }}" class="card max-w-2xl space-y-5 p-6">
        @csrf
        <x-admin.input label="Title" name="title" required />
        <x-admin.input label="Slug" name="slug" hint="Leave blank to auto-generate from the title. Must be unique." />
        <x-admin.select label="Template" name="template" :value="'default'" :options="['default' => 'Default', 'landing' => 'Landing Page', 'full-width' => 'Full Width']" required />
        <x-admin.select label="Status" name="status" :value="'draft'" :options="['draft' => 'Draft', 'in_review' => 'In Review', 'scheduled' => 'Scheduled', 'published' => 'Published', 'archived' => 'Archived']" required />
        <x-admin.checkbox label="Set as homepage" name="is_homepage" hint="Only one page can be the homepage. Setting this will unset any existing homepage." />

        <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
            <a href="{{ route('admin.pages.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">Create &amp; Continue to Sections</button>
        </div>
    </form>
</x-layouts.admin>
