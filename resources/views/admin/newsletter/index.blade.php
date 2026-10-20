<x-layouts.admin title="Newsletter Subscribers">
    <x-admin.page-header title="Newsletter Subscribers" description="Double opt-in subscribers collected from the public newsletter form." />

    <x-admin.filter-bar placeholder="Search by email...">
        <select name="status" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <option value="subscribed" @selected(request('status') === 'subscribed')>Subscribed</option>
            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
            <option value="unsubscribed" @selected(request('status') === 'unsubscribed')>Unsubscribed</option>
        </select>
    </x-admin.filter-bar>

    @if ($subscribers->isEmpty())
        <x-admin.empty-state title="No subscribers yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Subscribed</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscribers as $subscriber)
                        <tr>
                            <td class="font-medium">{{ $subscriber->email }}</td>
                            <td><x-admin.status-badge :status="$subscriber->status" /></td>
                            <td class="text-ink-muted">{{ $subscriber->subscribed_at?->format('M j, Y') ?? '—' }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <form method="POST" action="{{ route('admin.newsletter.update', $subscriber) }}">
                                        @csrf
                                        @method('PUT')
                                        @if ($subscriber->status === 'unsubscribed')
                                            <input type="hidden" name="status" value="subscribed">
                                            <button type="submit" class="btn-outline">Resubscribe</button>
                                        @else
                                            <input type="hidden" name="status" value="unsubscribed">
                                            <button type="submit" class="btn-outline">Unsubscribe</button>
                                        @endif
                                    </form>
                                    <x-admin.delete-button :action="route('admin.newsletter.destroy', $subscriber)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $subscribers->links() }}</div>
    @endif
</x-layouts.admin>
