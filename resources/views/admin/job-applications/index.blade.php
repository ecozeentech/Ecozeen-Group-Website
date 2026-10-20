<x-layouts.admin title="Job Applications">
    <x-admin.page-header title="Job Applications" description="Review applicants. Resumes are access-restricted and only downloadable by authorized staff." />

    <x-admin.filter-bar placeholder="">
        <select name="status" class="form-select sm:max-w-[160px]" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach (['new', 'reviewed', 'shortlisted', 'rejected', 'hired'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
            @endforeach
        </select>
    </x-admin.filter-bar>

    @if ($applications->isEmpty())
        <x-admin.empty-state title="No applications yet" />
    @else
        <div class="card overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td class="font-medium">{{ $application->full_name }}</td>
                            <td class="text-ink-muted">{{ $application->jobListing->title }}</td>
                            <td><x-admin.status-badge :status="$application->status" /></td>
                            <td class="text-ink-muted">{{ $application->created_at->diffForHumans() }}</td>
                            <td class="text-right">
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('admin.job-applications.show', $application) }}" class="btn-outline">Review</a>
                                    <x-admin.delete-button :action="route('admin.job-applications.destroy', $application)" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $applications->links() }}</div>
    @endif
</x-layouts.admin>
