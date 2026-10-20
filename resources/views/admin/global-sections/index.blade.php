<x-layouts.admin title="Reusable Sections">
    <x-admin.page-header title="Reusable Sections" description="Build a section once (e.g. a standard CTA or partner logo strip) and place it on multiple pages. Editing it here updates every page that uses it.">
        <x-slot:actions>
            <a href="{{ route('admin.global-sections.create') }}" class="btn-primary">+ New Reusable Section</a>
        </x-slot:actions>
    </x-admin.page-header>

    @if ($sections->isEmpty())
        <x-admin.empty-state title="No reusable sections yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Used on</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sections as $section)
                        <tr>
                            <td class="font-medium">{{ $section->name }}</td>
                            <td class="text-ink-muted">{{ \App\Support\PageBuilder\SectionTypeRegistry::label($section->type) }}</td>
                            <td class="text-ink-muted">{{ $section->pageSections()->count() }} page(s)</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.global-sections.edit', $section) }}" class="btn-outline">Edit</a>
                                    <x-admin.delete-button :action="route('admin.global-sections.destroy', $section)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $sections->links() }}</div>
    @endif
</x-layouts.admin>
