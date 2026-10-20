<x-layouts.admin :title="'Inquiry — '.$inquiry->full_name">
    <x-admin.page-header :title="$inquiry->full_name" :description="\Illuminate\Support\Str::headline($inquiry->inquiry_type)">
        <x-slot:actions>
            <a href="{{ route('admin.inquiries.index') }}" class="btn-outline">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card space-y-4 p-6 lg:col-span-2">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Email</p>
                    <p class="text-sm text-ink">{{ $inquiry->email }}</p>
                </div>
                @if ($inquiry->phone)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Phone</p>
                        <p class="text-sm text-ink">{{ $inquiry->phone }}</p>
                    </div>
                @endif
                @if ($inquiry->organization)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Organization</p>
                        <p class="text-sm text-ink">{{ $inquiry->organization }}</p>
                    </div>
                @endif
                @if ($inquiry->targetCompany)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Regarding</p>
                        <p class="text-sm text-ink">{{ $inquiry->targetCompany->name }}</p>
                    </div>
                @endif
                @if ($inquiry->jurisdiction)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Jurisdiction</p>
                        <p class="text-sm text-ink">{{ $inquiry->jurisdiction }}</p>
                    </div>
                @endif
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-ink-muted">Message</p>
                <p class="mt-1 whitespace-pre-line text-sm text-ink">{{ $inquiry->message }}</p>
            </div>
        </div>

        <div class="card space-y-4 p-6">
            <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <x-admin.select label="Status" name="status" :value="$inquiry->status" :options="['new' => 'New', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'spam' => 'Spam']" required />
                <x-admin.textarea label="Internal notes" name="internal_notes" :value="$inquiry->internal_notes" rows="6" />
                <button type="submit" class="btn-primary w-full">Save</button>
            </form>
            @if ($inquiry->handler)
                <p class="text-xs text-ink-muted">Last handled by {{ $inquiry->handler->name }}</p>
            @endif
        </div>
    </div>
</x-layouts.admin>
