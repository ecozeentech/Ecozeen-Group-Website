<x-layouts.admin title="Blog &amp; Articles">
    <x-admin.page-header title="Blog &amp; Articles" description="Manage Ecozeen Group's Insights blog.">
        <x-slot:actions>
            @can('blog.create')
                <a href="{{ route('admin.blog-posts.create') }}" class="btn-primary">+ New Post</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search posts...">
        <select name="status" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (['draft', 'in_review', 'scheduled', 'published', 'archived'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
            @endforeach
        </select>
    </x-admin.filter-bar>

    @if ($posts->isEmpty())
        <x-admin.empty-state title="No posts yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td class="font-medium">{{ $post->title }}</td>
                            <td class="text-ink-muted">{{ $post->category?->name }}</td>
                            <td class="text-ink-muted">{{ $post->author?->name }}</td>
                            <td><x-admin.status-badge :status="$post->status" /></td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    @if ($post->status === 'published')
                                        <a href="{{ route('blog.show', $post) }}" target="_blank" class="btn-outline">View</a>
                                    @endif
                                    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn-outline">Edit</a>
                                    @can('blog.publish')
                                        @if ($post->status !== 'published')
                                            <form method="POST" action="{{ route('admin.blog-posts.publish', $post) }}">
                                                @csrf
                                                <button type="submit" class="btn-primary">Publish</button>
                                            </form>
                                        @endif
                                    @endcan
                                    @can('blog.delete')
                                        <x-admin.delete-button :action="route('admin.blog-posts.destroy', $post)" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $posts->links() }}</div>
    @endif
</x-layouts.admin>
