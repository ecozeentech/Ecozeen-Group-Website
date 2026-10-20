<x-layouts.admin title="Products & Platforms">
    <x-admin.page-header title="Products & Platforms" description="Manage the group's live digital products and platforms.">
        <x-slot:actions>
            @can('products.create')
                <a href="{{ route('admin.products.create') }}" class="btn-primary">+ New Product</a>
            @endcan
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.filter-bar placeholder="Search products..." />

    @if ($products->isEmpty())
        <x-admin.empty-state title="No products yet" description="Add your first product or platform." />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead><tr><th>Name</th><th>Company</th><th>Status</th><th>Featured</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="font-medium">{{ $product->name }}</td>
                            <td class="text-ink-muted">{{ $product->company?->name ?? '—' }}</td>
                            <td><x-admin.status-badge :status="$product->status" /></td>
                            <td>{{ $product->is_featured ? 'Yes' : 'No' }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('products.show', $product) }}" target="_blank" class="btn-outline">View</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline">Edit</a>
                                    @can('products.delete')
                                        <x-admin.delete-button :action="route('admin.products.destroy', $product)" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    @endif
</x-layouts.admin>
