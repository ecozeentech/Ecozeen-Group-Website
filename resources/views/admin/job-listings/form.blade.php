@php($editing = $listing->exists)
<x-layouts.admin :title="($editing ? 'Edit' : 'New').' Job Listing'">
    <x-admin.page-header :title="($editing ? 'Edit' : 'New').' Job Listing'" />

    <form method="POST" action="{{ $editing ? route('admin.job-listings.update', $listing) : route('admin.job-listings.store') }}" class="card max-w-3xl space-y-5 p-6">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-admin.input label="Title" name="title" :value="$listing->title" required />
        <x-admin.input label="Slug" name="slug" :value="$listing->slug" hint="Leave blank to auto-generate." />
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-admin.select label="Company" name="company_id" :value="$listing->company_id" placeholder="— Group-wide —" :options="$companies->pluck('name', 'id')" />
            <x-admin.select label="Employment type" name="employment_type" :value="$listing->employment_type ?? 'full_time'" :options="['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'internship' => 'Internship']" required />
            <x-admin.input label="Department" name="department" :value="$listing->department" />
            <x-admin.input label="Location" name="location" :value="$listing->location" />
            <x-admin.select label="Status" name="status" :value="$listing->status ?? 'draft'" :options="['draft' => 'Draft', 'open' => 'Open', 'closed' => 'Closed']" required />
            <x-admin.input type="date" label="Closes on" name="closes_at" :value="$listing->closes_at?->format('Y-m-d')" />
        </div>
        <x-admin.textarea label="Description" name="description" :value="$listing->description" rows="8" required />
        <x-admin.textarea label="Requirements" name="requirements" :value="$listing->requirements" rows="6" />

        <div class="border-t border-surface-border pt-4">
            <h3 class="font-heading text-xs font-semibold uppercase tracking-wide text-ink-muted">SEO</h3>
            <div class="mt-3 space-y-4">
                <x-admin.input label="SEO title" name="seo_title" :value="$listing->seo_title" />
                <x-admin.textarea label="SEO description" name="seo_description" :value="$listing->seo_description" rows="2" />
            </div>
        </div>

        <div class="flex justify-end gap-2 border-t border-surface-border pt-4">
            <a href="{{ route('admin.job-listings.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary">{{ $editing ? 'Save changes' : 'Create listing' }}</button>
        </div>
    </form>
</x-layouts.admin>
