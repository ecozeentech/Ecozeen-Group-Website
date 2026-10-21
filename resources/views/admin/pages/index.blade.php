<x-layouts.admin title="Visual Editor">
    <x-admin.page-header title="Website content — visual editor" description="Build and manage every page on the public website. Open a page to assemble sections in the canvas.">
        <x-slot:actions>
            @can('pages.create')
                <a href="{{ route('admin.pages.create') }}" class="btn-secondary">+ New Page</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search pages...">
        <select name="status" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (['draft', 'in_review', 'scheduled', 'published', 'archived'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
            @endforeach
        </select>
    </x-admin.filter-bar>

    @if ($pages->isEmpty())
        <x-admin.empty-state title="No pages yet" description="Create your first page to start building the public website.">
            <x-slot:action>
                <a href="{{ route('admin.pages.create') }}" class="btn-secondary">+ New Page</a>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Homepage</th>
                        <th>Updated</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td class="font-medium">{{ $page->title }}</td>
                            <td class="text-ink-muted">/{{ $page->slug }}</td>
                            <td><x-admin.status-badge :status="$page->status" /></td>
                            <td>{{ $page->is_homepage ? 'Yes' : '—' }}</td>
                            <td class="text-ink-muted">{{ $page->updated_at->diffForHumans() }}</td>
                            <td class="text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    @if ($page->status === 'published')
                                        <a href="{{ $page->is_homepage ? route('home') : route('pages.show', $page->slug) }}" target="_blank" class="btn-outline">View</a>
                                    @endif
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn-outline">Edit</a>
                                    <form method="POST" action="{{ route('admin.pages.duplicate', $page) }}">
                                        @csrf
                                        <button type="submit" class="btn-outline">Duplicate</button>
                                    </form>
                                    @can('pages.publish')
                                        @if ($page->status === 'published')
                                            <form method="POST" action="{{ route('admin.pages.unpublish', $page) }}">
                                                @csrf
                                                <button type="submit" class="btn-outline">Unpublish</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.pages.publish', $page) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary">Publish</button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('pages.delete')
                                        <x-admin.delete-button :action="route('admin.pages.destroy', $page)" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $pages->links() }}</div>
    @endif
</x-layouts.admin>
