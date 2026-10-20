<x-layouts.admin title="Contact Inquiries">
    <x-admin.page-header title="Contact Inquiries" description="Inbound messages submitted through the public contact form." />

    <x-admin.filter-bar placeholder="">
        <select name="status" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (['new', 'in_progress', 'resolved', 'spam'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
            @endforeach
        </select>
        <select name="type" class="form-select sm:max-w-[200px]" onchange="this.form.submit()">
            <option value="">All inquiry types</option>
            @foreach (['institutional_investment', 'business_partnership', 'enterprise_it_services', 'commodity_trade', 'media_press', 'executive_careers', 'general'] as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ \Illuminate\Support\Str::headline($type) }}</option>
            @endforeach
        </select>
    </x-admin.filter-bar>

    @if ($inquiries->isEmpty())
        <x-admin.empty-state title="No inquiries yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquiries as $inquiry)
                        <tr>
                            <td class="font-medium">{{ $inquiry->full_name }}</td>
                            <td class="text-ink-muted">{{ \Illuminate\Support\Str::headline($inquiry->inquiry_type) }}</td>
                            <td class="text-ink-muted">{{ $inquiry->targetCompany?->name ?? '—' }}</td>
                            <td><x-admin.status-badge :status="$inquiry->status" /></td>
                            <td class="text-ink-muted">{{ $inquiry->created_at->diffForHumans() }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn-outline">Review</a>
                                    <x-admin.delete-button :action="route('admin.inquiries.destroy', $inquiry)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $inquiries->links() }}</div>
    @endif
</x-layouts.admin>
