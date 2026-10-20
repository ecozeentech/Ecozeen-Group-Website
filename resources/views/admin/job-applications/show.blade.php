<x-layouts.admin :title="'Application — '.$application->full_name">
    <x-admin.page-header :title="$application->full_name" :description="'Applied for '.$application->jobListing->title">
        <x-slot:actions>
            <a href="{{ route('admin.job-applications.index') }}" class="btn-outline">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card space-y-4 p-6 lg:col-span-2">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Email</p>
                <p class="text-sm text-ink">{{ $application->email }}</p>
            </div>
            @if ($application->phone)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Phone</p>
                    <p class="text-sm text-ink">{{ $application->phone }}</p>
                </div>
            @endif
            @if ($application->cover_letter)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Cover Letter</p>
                    <p class="whitespace-pre-line text-sm text-ink">{{ $application->cover_letter }}</p>
                </div>
            @endif
            @if ($application->resume)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Resume</p>
                    <a href="{{ route('admin.job-applications.resume', $application) }}" class="btn-outline mt-1 inline-block">Download Resume (PDF)</a>
                </div>
            @endif
        </div>

        <div class="card p-6">
            <h2 class="font-heading text-sm font-semibold text-ink">Status</h2>
            <form method="POST" action="{{ route('admin.job-applications.update', $application) }}" class="mt-4 space-y-4">
                @csrf
                @method('PUT')
                <x-admin.select label="Status" name="status" :value="$application->status" :options="['new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected', 'hired' => 'Hired']" required />
                <button type="submit" class="btn-primary w-full">Update Status</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
